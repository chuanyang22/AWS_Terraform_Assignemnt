# No IAM policy is created or attached here - the Academy LabRole already has
# broad CloudWatch permissions, so EC2 instances and this module can call
# PutMetricData / PutLogEvents without any extra grant.

resource "aws_sns_topic" "alerts" {
  name = "${var.name_prefix}-alerts"

  tags = {
    Name = "${var.name_prefix}-alerts"
  }
}

resource "aws_sns_topic_subscription" "email" {
  topic_arn = aws_sns_topic.alerts.arn
  protocol  = "email"
  endpoint  = var.alert_email
  # AWS emails a confirmation link to alert_email right after apply - alarms
  # will not deliver until that link is clicked once.
}

resource "aws_cloudwatch_log_group" "app" {
  name              = "/${var.name_prefix}/app"
  retention_in_days = var.log_retention_days

  tags = {
    Name = "${var.name_prefix}-app-logs"
  }
}

# Threshold set above the ASG's own scaling target so this only fires as a
# genuine "scaling isn't keeping up" signal, not on every routine scale-out.
resource "aws_cloudwatch_metric_alarm" "high_cpu" {
  alarm_name          = "${var.name_prefix}-asg-high-cpu"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = 3
  metric_name         = "CPUUtilization"
  namespace           = "AWS/EC2"
  period              = 60
  statistic           = "Average"
  threshold           = 85
  alarm_description   = "ASG average CPU above 85% for 3 consecutive minutes."
  alarm_actions       = [aws_sns_topic.alerts.arn]
  ok_actions          = [aws_sns_topic.alerts.arn]

  dimensions = {
    AutoScalingGroupName = var.asg_name
  }
}

resource "aws_cloudwatch_metric_alarm" "unhealthy_hosts" {
  alarm_name          = "${var.name_prefix}-alb-unhealthy-hosts"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = 2
  metric_name         = "UnHealthyHostCount"
  namespace           = "AWS/ApplicationELB"
  period              = 60
  statistic           = "Average"
  threshold           = 0
  alarm_description   = "One or more targets behind the ALB are unhealthy."
  alarm_actions       = [aws_sns_topic.alerts.arn]
  ok_actions          = [aws_sns_topic.alerts.arn]

  dimensions = {
    LoadBalancer = var.alb_arn_suffix
    TargetGroup  = var.target_group_arn_suffix
  }
}

resource "aws_cloudwatch_metric_alarm" "rds_low_storage" {
  alarm_name          = "${var.name_prefix}-rds-low-storage"
  comparison_operator = "LessThanThreshold"
  evaluation_periods  = 1
  metric_name         = "FreeStorageSpace"
  namespace           = "AWS/RDS"
  period              = 300
  statistic           = "Average"
  threshold           = 2000000000 # 2 GB in bytes
  alarm_description   = "RDS free storage below 2GB."
  alarm_actions       = [aws_sns_topic.alerts.arn]
  ok_actions          = [aws_sns_topic.alerts.arn]

  dimensions = {
    DBInstanceIdentifier = var.db_identifier
  }
}
resource ""aws_autoscaling_notification"" ""asg_notifications"" {
  group_names = [var.asg_name]

  notifications = [
    ""autoscaling:EC2_INSTANCE_LAUNCH"",
    ""autoscaling:EC2_INSTANCE_TERMINATE"",
    ""autoscaling:EC2_INSTANCE_LAUNCH_ERROR"",
    ""autoscaling:EC2_INSTANCE_TERMINATE_ERROR""
  ]

  topic_arn = aws_sns_topic.alerts.arn
}

data ""aws_iam_policy_document"" ""sns_topic_policy"" {
  statement {
    actions   = [""sns:Publish""]
    resources = [aws_sns_topic.alerts.arn]
    principals {
      type        = ""Service""
      identifiers = [""autoscaling.amazonaws.com""]
    }
  }
}

resource ""aws_sns_topic_policy"" ""alerts"" {
  arn    = aws_sns_topic.alerts.arn
  policy = data.aws_iam_policy_document.sns_topic_policy.json
}
