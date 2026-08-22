variable "name_prefix" {
  type    = string
  default = "assignment"
}


variable "secret_name" {
  type    = string
  default = "assignment-db-credentials"
}

variable "db_host" {
  type = string
}

variable "db_port" {
  type    = number
  default = 3306
}

variable "db_name" {
  type = string
}

variable "db_username" {
  type = string
}

variable "db_password" {
  type      = string
  sensitive = true
}
