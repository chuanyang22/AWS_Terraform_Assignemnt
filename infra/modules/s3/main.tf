locals {
  bucket_name = "${var.name_prefix}-s3-uploads"
}

resource "terraform_data" "uploads" {
  input = local.bucket_name

  provisioner "local-exec" {
    command = <<-EOT
      aws s3api create-bucket --bucket "${self.input}" --region us-east-1 || true
      aws s3api put-bucket-tagging --bucket "${self.input}" --tagging "TagSet=[{Key=Name,Value=${self.input}}]"
    EOT
  }

  provisioner "local-exec" {
    when    = destroy
    command = <<-EOT
      aws s3 rm "s3://${self.input}" --recursive || true
      aws s3api delete-bucket --bucket "${self.input}" --region us-east-1 || true
    EOT
  }
}

resource "aws_s3_bucket_public_access_block" "uploads" {
  bucket = local.bucket_name
  depends_on = [terraform_data.uploads]

  block_public_acls       = true
  ignore_public_acls      = true
  block_public_policy     = false
  restrict_public_buckets = false
}

resource "aws_s3_bucket_policy" "public_read" {
  bucket = local.bucket_name
  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid       = "PublicReadEventImages"
        Effect    = "Allow"
        Principal = "*"
        Action    = "s3:GetObject"
        Resource  = "arn:aws:s3:::${local.bucket_name}/${var.public_read_prefix}"
      }
    ]
  })

  depends_on = [terraform_data.uploads, aws_s3_bucket_public_access_block.uploads]
}

resource "aws_s3_bucket_cors_configuration" "uploads" {
  bucket = local.bucket_name
  depends_on = [terraform_data.uploads]

  cors_rule {
    allowed_headers = ["*"]
    allowed_methods = ["GET"]
    allowed_origins = ["*"]
    max_age_seconds = 3000
  }
}

