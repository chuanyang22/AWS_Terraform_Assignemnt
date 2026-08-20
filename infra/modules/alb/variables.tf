variable "name_prefix" {
  type    = string
  default = "assignment"
}

variable "vpc_id" {
  type = string
}

variable "public_subnet_ids" {
  description = "ALB must live in PUBLIC subnets - this is what makes it your only internet-facing entry point (requirement #8)."
  type        = list(string)
}

variable "alb_sg_id" {
  type = string
}

variable "app_port" {
  description = "Port the app listens on inside each EC2 instance."
  type        = number
  default     = 80
}

variable "health_check_path" {
  type    = string
  default = "/healthz.php"
}