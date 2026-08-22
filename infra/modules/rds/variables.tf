variable "name_prefix" {
  type    = string
  default = "assignment"
}

variable "private_subnet_ids" {
  description = "Must span at least 2 AZs — AWS requires this for the DB subnet group even when multi_az is false."
  type        = list(string)
}

variable "rds_sg_id" {
  type = string
}

variable "db_name" {
  type    = string
  default = "sport_facility_bookings_db"
}

variable "db_username" {
  type    = string
  default = "admin"
}

variable "db_password" {
  type      = string
  sensitive = true
}

variable "instance_class" {
  description = "Right-sized for a class assignment — do not over-provision."
  type        = string
  default     = "db.t3.micro"
}

variable "allocated_storage" {
  type    = number
  default = 20
}

variable "engine_version" {
  type    = string
  default = "8.0"
}