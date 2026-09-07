terraform {
  required_version = ">= 1.5.0"

  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "5.13.1"
    }
    random = {
      source  = "hashicorp/random"
      version = "~> 3.6"
    }
  }
}
provider "aws" {
  region = var.aws_region
}