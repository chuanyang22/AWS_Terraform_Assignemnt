output "bucket_id" {
  value = local.bucket_name
}

output "bucket_arn" {
  value = "arn:aws:s3:::${local.bucket_name}"
}

output "bucket_regional_domain_name" {
  value = "${local.bucket_name}.s3.amazonaws.com"
}
