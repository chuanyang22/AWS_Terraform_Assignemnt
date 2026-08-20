output "alb_dns_name" {
  description = "This is the ONLY URL you should use to access the website."
  value       = aws_lb.this.dns_name
}

output "target_group_arn" {
  value = aws_lb_target_group.app.arn
}

output "alb_arn_suffix" {
  value = aws_lb.this.arn_suffix
}

output "target_group_arn_suffix" {
  value = aws_lb_target_group.app.arn_suffix
}