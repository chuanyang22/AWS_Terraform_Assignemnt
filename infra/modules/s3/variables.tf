variable "name_prefix" {
  type = string
}

variable "public_read_prefix" {
  type = string
}

variable "tags" {
  type    = map(string)
  default = {}
}
