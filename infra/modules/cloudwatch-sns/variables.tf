variable "name_prefix" {
  type    = string
  default = "assignment"
}

variable "asg_name" {
  description = "Auto Scaling Group name to monitor for CPU alarms."
  type        = string
}

variable "alb_arn_suffix" {
  description = "ALB ARN suffix (e.g. app/name/abc123), required for CloudWatch dimensions - NOT the same as the full ARN or DNS name."
  type        = string
}

variable "target_group_arn_suffix" {
  description = "Target group ARN suffix, required for CloudWatch dimensions."
  type        = string
}

variable "db_identifier" {
  description = "RDS instance identifier to monitor for low free storage."
  type        = string
}

variable "alert_email" {
  description = "Email address to receive SNS alarm notifications. AWS sends a confirmation link here after apply - it must be clicked before alarms deliver."
  type        = string
}

variable "log_retention_days" {
  type    = number
  default = 7
}