module "vpc" {
  source = "../../modules/vpc"

  name_prefix           = var.name_prefix
  vpc_cidr              = var.vpc_cidr
  azs                   = var.azs
  public_subnet_cidrs   = var.public_subnet_cidrs
  private_subnet_cidrs  = var.private_subnet_cidrs
}

module "security_groups" {
  source = "../../modules/security-groups"

  name_prefix = var.name_prefix
  vpc_id      = module.vpc.vpc_id
  vpc_cidr    = module.vpc.vpc_cidr
}

module "s3" {
  source = "../../modules/s3"

  name_prefix        = var.name_prefix
  public_read_prefix = "uploads/*"
}

resource "random_password" "db" {
  length           = 24
  special          = true
  override_special = "!#$%^&*()-_=+"
}

module "rds" {
  source = "../../modules/rds"

  name_prefix        = var.name_prefix
  private_subnet_ids = module.vpc.private_subnet_ids
  rds_sg_id          = module.security_groups.rds_sg_id
  db_name            = var.db_name
  db_username        = var.db_username
  db_password        = random_password.db.result
}

module "secrets" {
  source = "../../modules/secrets"

  name_prefix = var.name_prefix
  secret_name = var.secret_name
  db_host     = module.rds.db_address
  db_port     = module.rds.db_port
  db_name     = var.db_name
  db_username = var.db_username
  db_password = random_password.db.result
}

module "alb" {
  source = "../../modules/alb"

  name_prefix        = var.name_prefix
  vpc_id             = module.vpc.vpc_id
  public_subnet_ids  = module.vpc.public_subnet_ids
  alb_sg_id          = module.security_groups.alb_sg_id
  health_check_path  = var.health_check_path
}

module "asg" {
  source = "../../modules/asg"

  name_prefix            = var.name_prefix
  vpc_id                 = module.vpc.vpc_id
  private_subnet_ids     = module.vpc.private_subnet_ids
  ec2_sg_id              = module.security_groups.ec2_sg_id
  target_group_arn       = module.alb.target_group_arn
  instance_type          = var.instance_type
  instance_profile_name  = var.instance_profile_name
  secret_arn             = module.secrets.secret_arn
  artifact_bucket        = module.s3.bucket_id
  artifact_key           = var.artifact_key
  aws_region             = var.aws_region
  min_size               = var.asg_min_size
  max_size               = var.asg_max_size
  desired_capacity       = var.asg_desired_capacity
}

module "cloudwatch_sns" {
  source = "../../modules/cloudwatch-sns"

  name_prefix              = var.name_prefix
  alb_arn_suffix           = module.alb.alb_arn_suffix
  target_group_arn_suffix  = module.alb.target_group_arn_suffix
  db_identifier            = module.rds.db_identifier
  asg_name                 = module.asg.asg_name
  alert_email              = var.alert_email
}