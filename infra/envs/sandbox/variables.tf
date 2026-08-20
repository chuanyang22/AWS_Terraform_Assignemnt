variable "aws_region" {
  description = "AWS region for all resources."
  type        = string
  default     = "us-east-1"
}

variable "name_prefix" {
  description = "Prefix applied to every resource name, per the {name_prefix}-{resource} convention."
  type        = string
  default     = "sport-facility-bookings"
}

variable "vpc_cidr" {
  type    = string
  default = "172.168.0.0/24"
}

variable "azs" {
  description = "Availability zones to spread subnets across."
  type        = list(string)
  default     = ["us-east-1a", "us-east-1b"]
}

variable "public_subnet_cidrs" {
  type    = list(string)
  default = ["172.168.1.0/24", "172.168.2.0/24"]
}

variable "private_subnet_cidrs" {
  type    = list(string)
  default = ["172.168.10.0/24", "172.168.11.0/24"]
}

variable "instance_type" {
  description = "EC2 instance type for app servers. Bump this later if t3.micro is insufficient - no other changes needed."
  type        = string
  default     = "t3.micro"
}

variable "instance_profile_name" {
  description = "Existing AWS Academy IAM instance profile (cannot create a new one in a Learner Lab account)."
  type        = string
  default     = "LabInstanceProfile"
}

variable "db_name" {
  type    = string
  default = "sport_facility_bookings_db"
}

variable "db_username" {
  type    = string
  default = "admin"
}

variable "secret_name" {
  type    = string
  default = "sport-facility-bookings-db-credentials"
}

variable "s3_bucket_name" {
  description = "Globally-unique bucket name for facility/booking image uploads."
  type        = string
  default     = "sport-facility-bookings-s3-uploads"
}

variable "artifact_key" {
  description = "S3 object key (within s3_bucket_name) that your deploy workflow uploads the app release artifact to."
  type        = string
  default     = "artifacts/sport-facility-bookings-app.zip"
}

variable "health_check_path" {
  type    = string
  default = "/healthz.php"
}

variable "asg_min_size" {
  type    = number
  default = 3
}

variable "asg_max_size" {
  type    = number
  default = 5
}

variable "asg_desired_capacity" {
  type    = number
  default = 3
}

variable "alert_email" {
  description = "Email address for SNS alarm notifications (CPU, unhealthy ALB targets, low RDS storage). AWS sends a confirmation link here after apply."
  type        = string
}