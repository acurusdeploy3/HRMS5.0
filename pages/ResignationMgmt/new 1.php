(select contact_email from notification_contact_email where employee_id in(
SELECT value FROM `application_configuration`
where  module='EMAIL' and config_type='PROCESSING' and parameter='HR_NOTIFY')) as hr,
date_format(date_of_submission_of_resignation,'%d %b %Y') as dosr,
date_format(date_of_leaving,'%d %b %Y') as dol
FROM `employee_resignation_information` er
inner join employee_details d on er.employee_id=d.employee_id
left join notification_contact_email n on er.employee_id=n.employee_id
left join notification_contact_email n1 on er.reporting_manager_id=n1.employee_id
where er.employee_id in(SELECT employee_id FROM `exit_employee_leavelop_tracker` where date(created_date_and_time)=date(now()))
and er.employee_id  =905
and er.is_active='Y')) as a;