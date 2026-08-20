# State bucket + lock table are bootstrapped manually once, BEFORE this
# backend can be used - Terraform can't create the backend it's about to
# store its own state in.
#
# IMPORTANT: S3 bucket names are GLOBALLY unique across ALL AWS accounts.
# Replace the bucket name below with your own unique one (e.g. add your
# account ID or a random suffix) and create that exact bucket first.
terraform {
  backend "s3" {
    bucket         = "sport-facility-bookings-tfstate" //--- REPLACE WITH YOUR OWN UNIQUE BUCKET NAME ---
    key            = "prod/terraform.tfstate"
    region         = "us-east-1"
    dynamodb_table = "sport-facility-bookings-tf-lock"
    encrypt        = true
  }
}