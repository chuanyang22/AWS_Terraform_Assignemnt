# All three groups are created bare (no inline ingress/egress) and then wired
# together with separate rule resources below. This avoids two problems the
# old inline-block style had:
#   1. A dependency cycle - alb-sg's egress needs ec2-sg's ID, and ec2-sg's
#      ingress needs alb-sg's ID. If both were inline blocks on the SG
#      resources themselves, Terraform would see alb-sg depend on ec2-sg AND
#      ec2-sg depend on alb-sg at the same time - a genuine "Error: Cycle"
#      that fails `terraform plan`. Separate rule resources break the cycle
#      because they're independent resources, not part of either parent SG.
#   2. Inline blocks silently replace ALL rules on a security group every
#      time you add or change one - separate rule resources change/add
#      individually, which is safer once you're editing this later.

resource "aws_security_group" "alb" {
  name        = "${var.name_prefix}-sg-alb"
  description = "The only security group allowed to receive traffic from the public internet"
  vpc_id      = var.vpc_id

  tags = {
    Name = "${var.name_prefix}-sg-alb"
  }
}

resource "aws_security_group" "ec2" {
  name        = "${var.name_prefix}-sg-ec2"
  description = "App instances: HTTP only from the ALB, SSH only from within the VPC"
  vpc_id      = var.vpc_id

  tags = {
    Name = "${var.name_prefix}-sg-ec2"
  }
}

resource "aws_security_group" "rds" {
  name        = "${var.name_prefix}-sg-rds"
  description = "Database: reachable only from app instances, nothing else"
  vpc_id      = var.vpc_id

  tags = {
    Name = "${var.name_prefix}-sg-rds"
  }
}

# ---------------------------------------------------------------------------
# alb-sg rules
# ---------------------------------------------------------------------------

# This is the ONLY rule in the whole stack allowed to use 0.0.0.0/0, and it's
# HTTP only - satisfies "no anywhere-IPv4 for non-HTTP" directly.
resource "aws_vpc_security_group_ingress_rule" "alb_http_from_internet" {
  security_group_id = aws_security_group.alb.id
  description        = "HTTP from internet"
  from_port           = 80
  to_port             = 80
  ip_protocol         = "tcp"
  cidr_ipv4           = "0.0.0.0/0"
}

# Egress restricted to exactly what the ALB needs to forward - not allow-all.
resource "aws_vpc_security_group_egress_rule" "alb_to_ec2" {
  security_group_id           = aws_security_group.alb.id
  description                 = "Forward HTTP to app instances only"
  from_port                   = 80
  to_port                     = 80
  ip_protocol                 = "tcp"
  referenced_security_group_id = aws_security_group.ec2.id
}

# ---------------------------------------------------------------------------
# ec2-sg rules
# ---------------------------------------------------------------------------

resource "aws_vpc_security_group_ingress_rule" "ec2_http_from_alb" {
  security_group_id           = aws_security_group.ec2.id
  description                 = "HTTP from ALB only"
  from_port                   = 80
  to_port                     = 80
  ip_protocol                 = "tcp"
  referenced_security_group_id = aws_security_group.alb.id
}

# Fallback shell access - scoped to the VPC CIDR, never the internet.
# Instances have no public IP, so this is unreachable from outside the VPC
# regardless; SSM Session Manager remains the primary access path.
resource "aws_vpc_security_group_ingress_rule" "ec2_ssh_from_vpc" {
  security_group_id = aws_security_group.ec2.id
  description        = "SSH from within the VPC only (SSM Session Manager is primary access)"
  from_port           = 22
  to_port             = 22
  ip_protocol         = "tcp"
  cidr_ipv4           = var.vpc_cidr
}

# HTTPS out - needed for the AWS CLI/SDK calls to Secrets Manager, S3 (for
# anything not covered by the VPC Gateway Endpoint), SSM, and CloudWatch.
resource "aws_vpc_security_group_egress_rule" "ec2_https_out" {
  security_group_id = aws_security_group.ec2.id
  description        = "HTTPS out - AWS API calls (Secrets Manager, SSM, CloudWatch)"
  from_port           = 443
  to_port             = 443
  ip_protocol         = "tcp"
  cidr_ipv4           = "0.0.0.0/0"
}

# HTTP out - Amazon Linux package mirrors that aren't S3-backed.
resource "aws_vpc_security_group_egress_rule" "ec2_http_out" {
  security_group_id = aws_security_group.ec2.id
  description        = "HTTP out - package updates"
  from_port           = 80
  to_port             = 80
  ip_protocol         = "tcp"
  cidr_ipv4           = "0.0.0.0/0"
}

# DB traffic only to the RDS security group - not allow-all.
resource "aws_vpc_security_group_egress_rule" "ec2_to_rds" {
  security_group_id           = aws_security_group.ec2.id
  description                 = "MySQL/Aurora to RDS only"
  from_port                   = 3306
  to_port                     = 3306
  ip_protocol                 = "tcp"
  referenced_security_group_id = aws_security_group.rds.id
}

# ---------------------------------------------------------------------------
# rds-sg rules
# ---------------------------------------------------------------------------

resource "aws_vpc_security_group_ingress_rule" "rds_from_ec2" {
  security_group_id           = aws_security_group.rds.id
  description                 = "MySQL/Aurora from EC2 app instances only"
  from_port                   = 3306
  to_port                     = 3306
  ip_protocol                 = "tcp"
  referenced_security_group_id = aws_security_group.ec2.id
}

# RDS never initiates outbound connections - no egress rule needed at all.
