-----------------------------------------------------------------------------------------------
-- Nidhi Lakhani (02 Jan 2020)
INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES
(NULL, 11, 'user.invite', 'Users Invitation', 'Users Invitation', 'user.invite', 1),
(NULL, 11, 'user.add.invite', 'Send Invitation to User', 'Form for sending invitation to user', 'user.add.invite', 0);

ALTER TABLE `j1app-dvpt`.`agency_contract` DROP INDEX `agency_id`, ADD INDEX `agency_id` (`agency_id`) USING BTREE;
ALTER TABLE `j1app-dvpt`.`agency_contract` DROP INDEX `user_id`, ADD INDEX `user_id` (`user_id`) USING BTREE;
ALTER TABLE `agency_contract` CHANGE `request_by` `request_by` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 = admin, 2 = user';
ALTER TABLE `agency` CHANGE `agency_type` `agency_type` TINYINT(1) NOT NULL COMMENT '1 = Registration, 2 = Placement, 3 = Sponsor, 4 = General';

INSERT INTO `email_notification` (`id`, `en_key`, `subject`, `text`, `send_to`, `send_cc`) VALUES (NULL, 'send_invitation_to_user', 'Send Invitation', "Hello<br/>\r\nDear candidate,\r\n<br/>\r\n<br/>\r\nYou\'re invited to join J-1 APPLICATION <br/>\r\nJ1 Application System (info@j1application.com) sent you this invitation. <br/>\r\n{{url}}<br/>\r\nPlease feel free to contact us by replying to this email or at<br/>\r\ninfo@j1application.com with any questions.<br/>\r\n<br/>\r\n<br/>\r\nRegards<br/>\r\nJ1 Application System<br/>\r\n", 1, 'test_cc_qa@yopmail.com');

INSERT INTO `menu_items` (`id`, `permission_group_id`, `permission_id`, `parent_id`, `title`, `route_name`, `menu_item_order`, `created_at`, `updated_at`) VALUES 
(NULL, 11, 88, 1, 'Users', 'user.list', 0, NOW(), NOW()),
(NULL, 11, 100, 2, 'Invite User', 'user.invite', 0, NOW(), NOW());

----------------------------------------------------------------------------------------------------------
-- Hetal Visaveliya (03 Jan 2020)

INSERT INTO `email_notification` (`id`, `en_key`, `subject`, `text`, `send_to`, `send_cc`) VALUES (NULL, 'activate_admin_account', 'Please activate your account.', 'Dear {{first_name}} {{last_name}},\r\n <br/><br/>\r\nThank you for your interest in the {{company}}. Please click on the link below to verify your email address. <br />\r\n<a href=\"{{url}}\">Click Here</a>\r\n <br/><br/> ', '2', 'test_cc_qa@yopmail.com');

INSERT INTO `system_settings` (`id`, `name`, `field`, `value`, `description`) VALUES (NULL, 'Admin URL', 'admin_url', 'https://admin.j1app.local.com/', 'Global Admin URL'), (NULL, 'Agency URL', 'agency_url', 'https://agency.j1app.local.com/', 'Global Agency URL');

UPDATE `email_notification` SET `en_key` = 'activate_user_account' WHERE `email_notification`.`id` = 1;

----------------------------------------------------------------------------------------------------------
--Manisha Odedara (06 Jan 2020)

ALTER TABLE `document_requirements` ADD `visibility` TINYINT(4) NOT NULL DEFAULT '3' COMMENT '1=admin,2=user,3=both' AFTER `document_section`;
ALTER TABLE `document_requirements` DROP `document_name`, DROP `document_label`;
ALTER TABLE `document_requirements` ADD `document_type` INT(11) NULL DEFAULT NULL COMMENT 'document_types-> id' AFTER `agency_id`;
ALTER TABLE `document_requirements` CHANGE `agency_id` `agency_id` INT(11) NULL COMMENT 'agency -> id';
ALTER TABLE `document_requirements` CHANGE `agency_id` `agency_id` INT(11) NULL DEFAULT '0' COMMENT 'agency -> id';

CREATE TABLE `document_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `doc_key` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `country_specific` int(11) NOT NULL COMMENT 'geo_country >> country_id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `document_types` ADD PRIMARY KEY (`id`);
ALTER TABLE `document_types` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `document_types` (`id`, `name`, `doc_key`, `description`, `template`, `country_specific`) VALUES
(1, 'Resume', 'resume', '', 'resume.jpg', 0),
(2, 'J1 Agreement', 'j1_agreement', '', '', 0),
(3, 'Photo', 'photo', '', 'photo.jpg', 0),
(4, 'Passport', 'passport', '', 'passport.pdf', 0),
(5, 'Aspire Rules and Regulations', 'aspire_rules_and_regulations', '', 'aspire_rules_and_regulations.doc', 0),
(6, 'Participant Eligibility Form', 'particip_elig_form', '', 'particip_elig_form.docx', 0),
(7, 'School Certificate', 'student_cert', '', 'student_cert.pdf', 0),
(8, 'Work Certificates', 'employer_ref_letter', '', 'trainee_ref_letter.pdf', 0),
(9, 'Diploma', 'j1_diploma', '', 'j1_diploma.pdf', 0),
(10, 'University/School Transcript', 'j1_transcript', '', '', 0),
(11, 'Proof of Eligibility Student Status', 'proof_of_student_status', '', '', 0),
(12, 'Terms and Conditions', 'terms_and_conditions', '', 'terms_and_conditions.pdf', 0),
(13, 'English Level Validation', 'english_level_validation', '', '', 0),
(14, 'Candidate Interview Report', 'cand_interview_report', '', 'cand_interview_report.doc', 0),
(15, 'Bank Statement', 'bank_statement', '', '', 0),
(16, 'Marriage Certificate (Original/ Foreign Language)', 'marriage_certificate', '', '', 0),
(17, 'Birth Certificate (Original/ Foreign Language)', 'birth_certificate', '', '', 0),
(18, 'ITN Drug Policy', 'itn_drug_policy', '', 'itn_drug_policy.doc', 0),
(19, 'Training Plan - DS 7002 (Signed by candidate)', 'ds7002_candidate', '', '', 0),
(20, 'Training Plan - DS 7002 (Empty Template) - Placement #1', 'ds7002_template', 'Exception', '', 0),
(21, 'Previous US Visa/s', 'previous_visas', 'Scan of every past US visa from the passport.', '', 0),
(22, 'Payment Plan Agreement', 'pay_agreem', '', 'pay_agreem.jpg', 0),
(23, 'Graduation Certificate', 'grad_certif', 'In place of diploma for recent graduates', '', 0),
(24, 'Orientation Presentation Verification From', 'Orientation_wt_cenet', '', 'orientation_wt_cenet.pdf', 0),
(25, 'Job Offer', 'job_offer', '', '', 0),
(26, 'Training Plan - DS 7002 (Empty Template) - Placement #2', 'training_plan_emp2', '', '', 0),
(27, 'Additional ITN Agreement', 'additional_itn_agreement', '', '', 0),
(28, 'ITN Interview Report', 'itn_interv_report', 'ITN template', '', 0),
(29, 'Training Plan - DS 7002 (Signed)', 'training_plan_signed', '', '', 0),
(30, 'Training Plan - DS 7002 (Signed) - Route 66 Employer #1', 'training_plan_signed_emp1', '', '', 0),
(31, 'Training Plan - DS 7002 (Signed) - Route 66 Employer #2', 'training_plan_signed_emp2', '', '', 0),
(32, 'Training Plan - DS 7002 (Signed) - COE and/or Extension', 'training_plan_signed_coe', '', '', 0),
(33, 'Sevis Receipt', 'sevis_receipt', '', '', 0),
(34, 'Financial Verification', 'Fin_verif_odc', '', '', 0),
(35, 'J1 Housing Regulations', 'housing_reg', '', '', 0),
(36, 'Exchange Visitor Information', 'Exchange_Visitor_Information', '', '', 0),
(37, 'Fee Disclosure Form', 'Fee_disclosure', '', '', 0),
(38, 'Previous DS2019  (Only if previous J1 visa)', 'Prev_ds_2019', '', '', 0),
(39, 'Previous DS 7002 (Only if previous J1 visa)', 'Prev_ds_7002', '', '', 0),
(40, 'ITN Agreement COE', 'ITN_Agreement_COE', '', '', 0),
(41, 'ITN Agreement CEO-E', 'ITN_Agreement_CEO_E', '', '', 0),
(42, 'ITN Agreement Extension', 'ITN_Agreement_Ext', '', '', 0),
(43, 'Marriage Certificate (In English)', 'marriage_certificate_english', 'Marriage Certificate (Translated in English)', '', 0),
(44, 'Birth Certificate (In English)', 'birth_certificate_english', 'Birth Certificate (Translated in English)', '', 0),
(45, 'Medical Certificate', 'medical_certificate', 'Medical Certificate Filipino Participants only', '', 169),
(46, 'Host Company Items', 'host_company_items', 'Host Company Items', '', 0),
(47, 'Spouse Passport', 'spouse_passport', 'Spouse Passport', '', 0),
(48, 'DS2019 Sevis Batch', 'DS2019_Sevis_Batch', 'DS2019 Sevis Batch', '', 0),
(49, 'Police Clearance', 'Police_Clearance', 'Police Clearance (for Indians Only)', '', 99),
(50, 'Affidavit as One', 'Affidavit_as_One', 'Affidavit as One (for Indians Only)', '', 99),
(51, 'Test Podium English Test Result', 'Test_Podium_English_Test_Result', 'Test Podium English Test Result', '', 0),
(52, 'Additional School Letter', 'Additional_School_Letter', 'Additional School Letter stating the relationship between current school and partner University', '', 0),
(53, 'Agreement and Orientation Supplement', 'Agreement_and_Orientation_Supplement', 'Agreement and Orientation Supplement', '', 99),
(54, 'Additional Training Certificates (if applicable)', 'additional_training_certificate', 'Additional Training Certificates (if applicable)', '', 0);

-----------------------------------------------------------------------------------------------
-- Priyank Khunt (06 January 2020)

ALTER TABLE `portfolio` 
    ADD `as_order_key` VARCHAR(255) NULL COMMENT 'application_status_steps -> as_order_key' AFTER `portfolio_status`,
    ADD `is_step_locked` TINYINT(1) NULL DEFAULT '0' COMMENT '0 = unlock, 1 = lock' AFTER `as_order_key`;


ALTER TABLE `user_details` 
    ADD `industry_selected` SMALLINT NULL COMMENT 'This field stores selected industry type' AFTER `twitter_url`, 
    ADD `eligibility_test_result` SMALLINT NULL COMMENT 'This field stores program id' AFTER `industry_selected`, 
    ADD `eligibility_test_output` SMALLINT NULL COMMENT 'This field stores the result of elibility test' AFTER `eligibility_test_result`;

INSERT INTO `j1_statuses` (`id`, `status_key`, `status_name`, `category`) VALUES
(1011, 'registration-fee-collected', 'Registration Fee Collected', 1);


RENAME TABLE `resume_eduback` TO `resume_education`;
RENAME TABLE `resume_exp` TO `resume_employment`;


CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `program_name` varchar(200) NOT NULL,
  `dos_program_category_id` tinyint(1) NOT NULL COMMENT 'Ref key of dos_program_category table',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Active, 0 = Deactive'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `programs` (`id`, `program_name`, `dos_program_category_id`, `status`) VALUES
(1, 'Intern - Current Student', 1, 1),
(2, 'Intern - Recent Graduate', 1, 1),
(3, 'Trainee - Recent Graduate', 2, 1),
(4, 'Trainee - Young Professional', 2, 1),
(5, 'W&T', 0, 1);


ALTER TABLE `portfolio` ADD `program_id` INT NOT NULL DEFAULT '0' COMMENT 'programs >> id' AFTER `user_id`;

----------------------------------------------------------------------------------------------------------
-- Hetal Visaveliya (07 January 2020)

INSERT INTO `permissions` (`permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES
(11, 'user.app.list', 'User Application List', 'Manage User Application List', 'user.app.list', 1),
(11, 'user.app.progress', 'User Application Progress', 'Manage User Application Progress', 'user.app.progress', 0);

INSERT INTO `menu_items` (`permission_group_id`, `permission_id`, `parent_id`, `title`, `route_name`, `menu_item_order`, `created_at`, `updated_at`) VALUES 
(11, 102, 1, 'User Application List', 'user.app.list', 2, NOW(), NOW());

---------------------------------------------------------------------------------------------------------
-- Priyank Khunt (08 January 2020)

DROP TABLE `host_companies`;

CREATE TABLE `host_companies` (
  `id` int(11) NOT NULL,
  `hc_name` varchar(255) NOT NULL,
  `hc_id_number` varchar(255) NOT NULL COMMENT '(EIN) Host Company Id Number',
  `hc_description` varchar(255) DEFAULT NULL,
  `hc_street` varchar(255) DEFAULT NULL,
  `hc_suite` varchar(255) DEFAULT NULL,
  `hc_city` varchar(255) DEFAULT NULL,
  `hc_state` varchar(255) DEFAULT NULL,
  `hc_zip` varchar(255) DEFAULT NULL,
  `contact_first_name` varchar(255) DEFAULT NULL,
  `contact_last_name` varchar(255) DEFAULT NULL,
  `contact_title` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_skype` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `contact_phone_extension` varchar(255) DEFAULT NULL,
  `contact_fax` varchar(255) DEFAULT NULL,
  `contact_website` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL COMMENT 'admins -> id',
  `status` enum('1','2','3') NOT NULL DEFAULT '3' COMMENT '1 = Active, 2 = Deactive, 3 = Under Review',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `host_companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `status` (`status`);
  
ALTER TABLE `host_companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `documents` ADD `document_type` INT NULL COMMENT 'document_types-> id' AFTER `portfolio_id`;

INSERT INTO `email_notification` (`id`, `en_key`, `subject`, `text`, `send_to`, `send_cc`) 
VALUES
(null, 'document_uploaded', 'Document Uploaded', 'Dear {{first_name}} {{last_name}},\r\n<br/><br/>\r\n Thank you for uploading your {{document_label}}, our registration coordinator will contact you shortly .<br>\r\n<a href=\"{{url}}\">Click Here</a> to continue your Registration Process.', 1, 'test_cc_qa@yopmail.com');


ALTER TABLE `user_details` ADD `reg_fee_status` TINYINT(1) NULL DEFAULT '0' COMMENT '0 = none, 1 = charged, 2 = postpone' AFTER `eligibility_test_output`;
---------------------------------------------------------------------------------------------------------
-- Nidhi Lakhani (09 January 2020)
ALTER TABLE `forum_topics` CHANGE `forum_subcategory_id` `forum_category_id` INT(11) NOT NULL COMMENT 'forum_category -> id';
ALTER TABLE `forum_topics` ADD `notify_me_of_replies` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '0 = No, 1 = Yes' AFTER `tags`;

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES
(NULL, 7, 'forum.subcat.search', 'Forum Sub Category Search', 'Forum sub sategory search', 'forum.subcat.search', 0),
(NULL, 7, 'topic.search', 'Forum Topic Search', 'Forum topic search', 'topic.search', 0),
(NULL, 7, 'load.topic.data', 'To Get Topic Data', 'Get topic data like follow,like,reply,view', 'load.topic.data', 0);
---------------------------------------------------------------------------------------------------------
-- Hetal Visaveliya (09 January 2020)

INSERT INTO `menu_items` (`permission_group_id`, `permission_id`, `parent_id`, `title`, `route_name`, `menu_item_order`, `created_at`, `updated_at`) VALUES ('12', '94', '', 'FAQ Manager', 'faq.list', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

---------------------------------------------------------------------------------------------------------
-- Manish Bodar (09 January 2020)

ALTER TABLE `admins` ADD `theme_color` VARCHAR(255) NULL DEFAULT NULL AFTER `remember_token`;

----------------------------------------------------------------------------------------------------------
--Manisha Odedara (10 January 2020)

ALTER TABLE `forum_topic_follow` ADD `is_report` TINYINT(4) NULL DEFAULT '0' COMMENT '0=Not Reported,1=Reported' AFTER `notification_status`;
ALTER TABLE `forum_topic_follow` ADD `report_reason` VARCHAR(244) NULL DEFAULT NULL AFTER `is_report`;

-----------------------------------------------------------------------------------------------
-- Priyank Khunt (10 January 2020)

UPDATE `document_types` SET 
`name` = 'Passport Photo',
`doc_key` = 'passport_photo',
`template` = '' 
WHERE `document_types`.`doc_key` = 'photo';


ALTER TABLE `resume` CHANGE `passport_size_image` `passport_photo` VARCHAR(255) NULL COMMENT 'Passport Size Image';

ALTER TABLE `resume_education` DROP `user_id`;
ALTER TABLE `resume_employment` DROP `user_id`;
ALTER TABLE `resume_certificates` DROP `user_id`;
ALTER TABLE `resume_award` DROP `user_id`;


ALTER TABLE `resume_employment` 
    CHANGE `property` `employer_name` VARCHAR(255) NULL,
    CHANGE `title` `title` VARCHAR(255) NULL, 
    CHANGE `duties` `duties` VARCHAR(255) NULL, 
    CHANGE `location` `location` VARCHAR(255) NULL, 
    CHANGE `start_date` `start_date` DATE NULL, 
    CHANGE `end_date` `end_date` DATE NULL;

ALTER TABLE `resume_certificates` 
    CHANGE `title` `title` VARCHAR(255) NULL, 
    CHANGE `description` `description` VARCHAR(255) NULL, 
    CHANGE `location` `location` VARCHAR(255) NULL, 
    CHANGE `date_of_certificate` `date_of_certificate` DATE NULL;

ALTER TABLE `resume_award` 
    CHANGE `title` `title` VARCHAR(255) NULL, 
    CHANGE `description` `description` VARCHAR(255) NULL, 
    CHANGE `award_date` `award_date` DATE NULL;
-----------------------------------------------------------------------------------------------
-- Nidhi Lakhani (10 January 2020)
ALTER TABLE `email_notification` ADD `status` TINYINT(1) NOT NULL COMMENT '1 = Active, 0 = Deactive' AFTER `send_cc`;

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES
(NULL, 13, 'notification.type.list', 'Notification List', 'Notification List', 'notification.type.list', 1),
(NULL, 13, 'email.notification.list', 'Email Notification List', 'Email Notification List', 'email.notification.list', 1),
(NULL, 13, 'notification.message.list', 'Notification Message List', 'Notification Message List', 'notification.message.list', 1),
(NULL, 13, 'notification.type.status', 'Change Notification Type Status', 'Change notification type status', 'notification.type.status', 0),
(NULL, 13, 'notification.type.edit.form', 'Notification Type Edit', 'Notification Type edit form', 'notification.type.edit.form', 0),
(NULL, 13, 'notification.type.edit', 'Notification Type Edit', 'Update existing notification type data', 'notification.type.edit', 0),
(NULL, 13, 'notification.type.delete', 'Notification Type Delete', 'Delete notification type from the database', 'notification.type.delete', 0),
(NULL, 13, 'notification.message.search', 'Notification Message Search', 'Search notification message from the database', 'notification.message.search', 0),
(NULL, 13, 'notification.message.status', 'Change Notification Message Status', 'Change notification message status', 'notification.message.status', 0),
(NULL, 13, 'notification.message.edit.form', 'Notification Message Edit', 'Notification message edit form', 'notification.message.edit.form', 0),
(NULL, 13, 'notification.message.edit', 'Notification Message Edit', 'Update existing notification message data', 'notification.message.edit', 0),
(NULL, 13, 'notification.message.delete', 'Notification Message Delete', 'Delete notification message from the database', 'notification.message.delete', 0),
(NULL, 13, 'email.notification.search', 'Email Notification Search', 'Search email notification from the database', 'email.notification.search', 0),
(NULL, 13, 'email.notification.status', 'Change Email Notification Status', 'Change email notification status', 'email.notification.status', 0),
(NULL, 13, 'email.notification.edit.form', 'Email Notification Edit', 'Email Notification edit form', 'email.notification.edit.form', 0),
(NULL, 13, 'email.notification.edit', 'Email Notification Edit', 'Update existing email notification data', 'email.notification.edit', 0),
(NULL, 13, 'email.notification.delete', 'Email Notification Delete', 'Delete email notification from the database', 'email.notification.delete', 0);

INSERT INTO `menu_items` (`id`, `permission_group_id`, `permission_id`, `parent_id`, `title`, `route_name`, `menu_item_order`, `created_at`, `updated_at`) VALUES 
(NULL, 13, 104, 0, 'Notification Type', 'notification.type.list', 2, NOW(), NOW()),
(NULL, 13, 112, 0, 'Notification Message', 'notification.message.list', 3, NOW(), NOW()),
(NULL, 13, 113, 0, 'Email Notification', 'email.notification.list', 1, NOW(), NOW());

INSERT INTO `notification_types` (`id`, `notification_key`, `notification_name`, `status`, `visible_to_user`, `notification_mode`) VALUES
(1, 'messages', 'Messages', 1, 1, 1),
(2, 'connection', 'Connection (Friend requests)', 1, 1, 1),
(3, 'forums', 'Forums', 1, 1, 1),
(4, 'application_status', 'Update to Your Application Status', 1, 0, 1),
(5, 'tagged', 'You being tagged', 1, 1, 1),
(6, 'support_system', 'Answers to your Help Center questions', 1, 0, 0),
(7, 'new_itn_features', 'New ITN features', 1, 1, 1),
(8, 'invitation_to_participate', 'Invitations to participate in ITN research', 1, 1, 1),
(9, 'other', 'Other', 1, 1, 0);

INSERT INTO `notification_messages` (`id`, `notification_type_id`, `notification_type_key`, `notification_type_data`, `notification_text`, `notification_message`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '1_resume', '1_resume', 'Application Status: Resume Upload', 'You completed your eligibility test successfully, Now please upload your resume.', 1, '2019-07-22 18:30:00', '2019-08-06 12:52:33'),
(2, 4, '1_resume_approved', '1_resume_approved', 'Application Status: Resume Status', 'Your Resume has been {{document_status}}', 1, '2019-07-22 18:30:00', '2019-08-08 10:00:12'),
(3, 4, '1_skype', '1_skype', 'Application Status: Skype', 'Please enter your skype Id', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(4, 4, '1_itn_interview_msg_1', '1_itn_interview', 'Application Status: Pre-Screen Interview', 'Your ITN pre-screening Interview has been scheduled', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(5, 4, '1_itn_interview_msg_2', '1_itn_interview', 'Application Status: Pre-Screen Interview', 'You are not eligible for the J1 program', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(6, 4, '1_itn_interview_msg_3', '1_itn_interview', 'Application Status: Pre-Screen Interview', 'Your pre-screening interview has been completed successfully', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(7, 4, '1_registration_fee', '1_registration_fee', 'Application Status: Registration Fee', 'Your registration fee has been collected and verified', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(8, 4, '1_additional_info', '1_additional_info', 'Application Status: Additional Information', 'Please enter your missing information on your profile', 1, '2019-07-22 18:30:00', '2020-01-09 04:38:48'),
(9, 4, '1_searching_position_msg_1', '1_searching_position', 'Application Status: Searching Position', 'Get ready for interview with Host Company', 1, '2019-07-22 18:30:00', '2019-08-08 07:05:14'),
(10, 4, '1_searching_position_msg_2', '1_searching_position', 'Application Status: Searching Position', 'Not Selected by Host company', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(11, 4, '1_searching_position_msg_3', '1_searching_position', 'Application Status: Searching Position', 'Rejected by Host Company', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(12, 4, '1_booked_msg_1', '1_booked', 'Application Status: Host Company Reviewing/Interview', 'One host company is reviewing your profile', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(13, 4, '1_booked_msg_2', '1_booked', 'Application Status: Host Company Reviewing/Interview', 'Your interview with {{hc_name}} has been scheduled successfully', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(14, 4, '1_placed', '1_placed', 'Application Status: Placement', 'Congratulation! {{hc_name}} has confirmed your placement', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(15, 4, '1_ds7002_tp_created', '1_ds7002_tp_created', 'Application Status: DS7002 Training Plan', 'Your training placement plan (DS7002) was created for your training at {{hc_name}}', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(16, 4, '1_ds7002_tp_signed', '1_ds7002_tp_signed', 'Application Status: DS7002 Training Plan Signed', 'Your training placement plan (DS7002) has been signed successfully', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(17, 4, '1_ds2019_sent', '1_ds2019_sent', 'Application Status: DS2019 Sent', 'Your DS2019 form has been sent', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(18, 4, '1_predeparture_orientation_msg_1', '1_predeparture_orientation', 'Application Status: Pre-Departure Orientation', 'Pre Departure Orientation Link Sent', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(19, 4, '1_predeparture_orientation_msg_2', '1_predeparture_orientation', 'Application Status: Pre-Departure Orientation', 'You have {{result}} your orientation quiz test', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(20, 4, '1_us_embassy_interview', '1_us_embassy_interview', 'Application Status: Embassy Interview', 'Please fill up your embassy interview schedule details', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(21, 4, '1_us_visa_msg_1', '1_us_visa', 'Application Status: US Embassy Outcome', 'Please add the outcome of your embassy interview', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(22, 4, '1_us_visa_msg_2', '1_us_visa', 'Application Status: US Embassy Outcome', 'Please quit your program due to consequent visa rejection', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(23, 4, '1_flight_info', '1_flight_info', 'Application Status: Flight Information', 'Please update your arrival information', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(24, 4, '1_arrival', '1_arrival', 'Application Status: Arrived', 'Welcome to USA', 1, '2019-07-22 18:30:00', '2020-01-09 04:43:07'),
(25, 4, '1_arrival_check_in', '1_arrival_check_in', 'Application Status: Arrival Check In', 'Please complete your arrival check in', 1, '2019-07-22 18:30:00', '2020-01-09 05:45:17'),
(26, 4, '1_monthly_check_in', '1_monthly_check_in', 'Application Status: Monthly Check In', 'Please complete your monthly check in', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(27, 4, '1_review_your_mid_supervisor_evaluation', '1_review_your_mid_supervisor_evaluation', 'Application Status: Midterm Supervisor Evaluation', 'Please review your midterm supervisor evaluation', 1, '2019-07-22 18:30:00', '2019-07-22 18:30:00'),
(28, 4, '1_review_your_final_supervisor_evaluation', '1_review_your_final_supervisor_evaluation', 'Application Status: Final Supervisor Evaluation', 'Please review your final term supervisor evaluation', 1, '2019-07-22 18:30:00', '2020-01-09 05:47:11'),
(29, 9, 'other_email_verification', 'other_email_verification', 'Email Verification', 'Your email verified successfully!', 1, '2019-07-22 18:30:00', '2020-01-09 04:41:00'),
(30, 9, 'status_on_hold', 'other_status_update', 'Alert! Status Update', 'Your application is on hold', 1, '2019-07-22 18:30:00', '2020-01-09 05:44:09'),
(31, 9, 'status_pla_cancelled', 'other_status_update', 'Alert! Status Update', 'Sorry! Your Placement was cancelled by Host Company', 1, '2019-07-22 18:30:00', '2020-01-09 05:47:17'),
(32, 9, 'other_document_updates', 'other_document_updates', 'Document Updates', 'Your {{document_name}} document is {{action}}', 1, '2019-07-22 18:30:00', '2020-01-09 05:47:19');

-----------------------------------------------------------------------------------------------
-- Priyank Khunt (13 January 2020)


UPDATE `j1_statuses` SET 
    `status_key` = 'registration-fee-completed',
    `status_name` = 'Registration Fee Completed' 
WHERE `j1_statuses`.`status_key` = 'registration-fee-collected';


UPDATE `j1_statuses` SET 
    `status_key` = 'j1-agreement-signed',
    `status_name` = 'J1 Agreement Signed' 
WHERE `j1_statuses`.`status_key` = 'j1-agreement';

INSERT INTO `email_notification` (`id`, `en_key`, `subject`, `text`, `send_to`, `send_cc`) 
VALUES
(null, 'request_financing', 'Request for Payment Plan', '<p>Dear Admin,<br />\r\n<br />\r\nMy self {{candidate_name}} and I am looking for payment plan.<br />\r\n<br />\r\nThanks</p>', 2, 'test_cc_qa@yopmail.com');



ALTER TABLE `user_details` 
    ADD `field_studied` VARCHAR(255) NOT NULL AFTER `reg_fee_status`, 
    ADD `gender` TINYINT(1) NOT NULL COMMENT '1 = Male, 2 = Female, 3 = Other' AFTER `field_studied`,
    ADD `payment_plan` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = none, 1 = Full Payment (One Time Payment Upfront), 2 = Payment Plan (Monthly Instalment)' AFTER `gender`;

ALTER TABLE `user_details` DROP `reg_fee_status`;


INSERT INTO `application_status_steps` (`id`, `as_stage_number`, `as_order_key`, `as_order`, `as_title`, `as_icon`, `as_desc_before`, `as_desc_current`, `as_desc_after`, `j1_status_id`) VALUES
(null, 1, '1_registration_fee', 7, 'Registration Fee', 'fa-dollar', 'Before', 'Current', 'After', '1009');

UPDATE `application_status_steps` SET `as_icon` = 'fa-check-square-o' WHERE `application_status_steps`.`as_order_key` = '1_eligibility_test';
UPDATE `application_status_steps` SET `as_icon` = 'fa-file-text-o' WHERE `application_status_steps`.`as_order_key` = '1_resume_upload';
UPDATE `application_status_steps` SET `as_icon` = 'fa-file-text' WHERE `application_status_steps`.`as_order_key` = '1_resume_approval';
UPDATE `application_status_steps` SET `as_icon` = 'fa-skype' WHERE `application_status_steps`.`as_order_key` = '1_skype';
UPDATE `application_status_steps` SET `as_icon` = 'fa-user' WHERE `application_status_steps`.`as_order_key` = '1_j1_interview';
UPDATE `application_status_steps` SET `as_icon` = 'fa-file-text-o' WHERE `application_status_steps`.`as_order_key` = '1_j1_agreement';
UPDATE `application_status_steps` SET 
        `as_icon` = 'fa-info-circle', 
        `as_order` = '8' 
    WHERE `application_status_steps`.`as_order_key` = '1_additional_info';
UPDATE `application_status_steps` SET `as_icon` = 'fa-dollar' WHERE `application_status_steps`.`as_order_key` = '1_registration_fee';

CREATE TABLE `j1_interview` (
  `id` int(11) NOT NULL,
  `portfolio_id` int(11) NOT NULL,
  `date_interview_admin` datetime NOT NULL,
  `time_zone_admin` int(11) NOT NULL,
  `date_interview_user` datetime NOT NULL,
  `time_zone_user` int(11) NOT NULL,
  `interview_scheduled_by` int(11) NOT NULL COMMENT 'admins >> id',
  `graduation_date` date DEFAULT NULL,
  `availability_date` date DEFAULT NULL,
  `availability_type` tinyint(1) DEFAULT NULL COMMENT '1 = Flexible, 2 = Mandatory, 3 = No later than',
  `preferred_program_length` decimal(10,1) DEFAULT NULL,
  `preferred_position_1` int(11) DEFAULT NULL,
  `preferred_position_2` int(11) DEFAULT NULL,
  `english_level` float(10,1) DEFAULT NULL,
  `has_passport` tinyint(1) DEFAULT NULL COMMENT '1 = have own passport, 2 = not have own passport',
  `previous_us_visas` tinyint(1) DEFAULT NULL COMMENT '0 = No, 1 = Yes',
  `reg_fee_status` tinyint(1) DEFAULT NULL COMMENT '1 = Charge,2 = Postpone',
  `interview_additonal_info` varchar(255) DEFAULT NULL,
  `interview_date` datetime DEFAULT NULL,
  `interviewed_by` int(11) DEFAULT NULL COMMENT 'admins >> id',
  `interview_status` tinyint(1) NOT NULL COMMENT '1 = Interview Scheduled, 2 = Interview Finished',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `j1_interview`
  ADD PRIMARY KEY (`id`),
  ADD KEY `portfolio_id` (`portfolio_id`),
  ADD KEY `interview_scheduled_by` (`interview_scheduled_by`),
  ADD KEY `interviewed_by` (`interviewed_by`);

ALTER TABLE `j1_interview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-----------------------------------------------------------------------------------------------
--Manisha Odedara (15 January 2020)

DROP TABLE `user_details`;

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'users -> id',
  `portfolio_id` int(11) NOT NULL COMMENT 'portfolio -> id',
  `skype_id` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `industry_selected` smallint(6) DEFAULT NULL COMMENT 'This field stores selected industry type',
  `eligibility_test_result` smallint(6) DEFAULT NULL COMMENT 'This field stores program id',
  `eligibility_test_output` smallint(6) DEFAULT NULL COMMENT 'This field stores the result of elibility test',
  `phone_number` varchar(255) DEFAULT NULL,
  `phone_number_two` varchar(255) DEFAULT NULL,
  `best_call_time` tinyint(4) DEFAULT NULL COMMENT '1=morning,2=afternoon,3=evening',
  `gender` tinyint(4) DEFAULT NULL COMMENT '1=male,2=female,3=other',
  `secondary_email` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `street_2` varchar(255) DEFAULT NULL,
  `in_care_of` varchar(255) DEFAULT NULL,
  `deliver_my_name` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_city` varchar(255) DEFAULT NULL,
  `birth_country` int(11) DEFAULT NULL,
  `passport_number` varchar(255) DEFAULT NULL,
  `passport_issued` date DEFAULT NULL,
  `passport_expires` date DEFAULT NULL,
  `country_citizen` int(11) DEFAULT NULL,
  `country_resident` int(11) DEFAULT NULL,
  `country_issuer` int(11) DEFAULT NULL,
  `previously_participated` int(11) DEFAULT NULL,
  `j1_first_name` varchar(255) DEFAULT NULL,
  `j1_first_started` date DEFAULT NULL,
  `j1_first_ended` date DEFAULT NULL,
  `j2_second_name` varchar(255) DEFAULT NULL,
  `j2_second_started` date DEFAULT NULL,
  `j2_second_ended` date DEFAULT NULL,
  `material_status` int(11) DEFAULT NULL COMMENT '1=single,2=married,3=divorced,4=widowed',
  `spouse_dep_needs_j2` tinyint(4) DEFAULT NULL COMMENT '1=no,2=yes',
  `spouse_dep_last_name` varchar(255) DEFAULT NULL,
  `spouse_dep_first_name` varchar(255) DEFAULT NULL,
  `spouse_dep_middle_name` varchar(255) DEFAULT NULL,
  `spouse_dep_gender` tinyint(4) DEFAULT NULL COMMENT '1=male,2=female',
  `spouse_dep_birth_date` date DEFAULT NULL,
  `spouse_dep_birth_city` varchar(255) DEFAULT NULL,
  `spouse_dep_birth_country` int(11) DEFAULT NULL,
  `other_dependants` tinyint(4) DEFAULT NULL COMMENT '1=yes,2=no',
  `spouse_dep_us_entry_together` tinyint(4) DEFAULT NULL COMMENT '1=yes,2=no',
  `spouse_dep_entry_date` date DEFAULT NULL,
  `currently_student` tinyint(4) DEFAULT NULL COMMENT '1=yes,2=no',
  `institution` varchar(255) DEFAULT NULL,
  `institution_type` int(11) DEFAULT NULL,
  `study_level` int(11) DEFAULT NULL,
  `program_start` date DEFAULT NULL,
  `program_end` date DEFAULT NULL,
  `advance_completed` varchar(255) DEFAULT NULL,
  `experience_year` int(11) DEFAULT NULL,
  `payment_plan` tinyint(4) DEFAULT '0' COMMENT '0 = none, 1 = Full Payment (One Time Payment Upfront), 2 = Payment Plan (Monthly Instalment)',
  `field_studied` varchar(255) DEFAULT NULL,
  `currently_employed` tinyint(4) DEFAULT NULL COMMENT '1=yes,2=no',
  `employer_name` varchar(255) DEFAULT NULL,
  `employer_address` varchar(255) DEFAULT NULL,
  `total_employees` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `sup_name` varchar(255) DEFAULT NULL,
  `employer_phone` varchar(255) DEFAULT NULL,
  `employer_fax` varchar(255) DEFAULT NULL,
  `computer_programs` varchar(255) DEFAULT NULL,
  `emp_start_date` date DEFAULT NULL,
  `contact_name_first` varchar(255) DEFAULT NULL,
  `contact_name_last` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `contact_phone_alternative` varchar(255) DEFAULT NULL,
  `contact_relationship` int(11) DEFAULT NULL,
  `contact_country` int(11) DEFAULT NULL,
  `contact_english_speaking` tinyint(4) DEFAULT NULL COMMENT '1=yes,2=no',
  `contact_language` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `criminal_record` tinyint(4) DEFAULT NULL COMMENT '1=yes,2=no',
  `criminal_explanation` varchar(255) DEFAULT NULL,
  `lock_additional_info` int(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolio_id` (`portfolio_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `country` (`country`);


ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

------------------------------------------------------------------------------------------------------------Hetal Visaveliya (16 January 2020)

INSERT INTO `permissions` (`permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES 
('6', 'document.reject.reason.form', 'Document Reject Reason Form', 'Document Reject Reason Form', 'document.reject.reason.form', NULL), 
('6', 'document.reject.reason', 'Store Document Reject Reason', 'Store Document Reject Reason', 'document.reject.reason', NULL);

----------------------------------------------------------------------------------------------------------
--Hetal Visaveliya (17 January 2020)

CREATE TABLE `position_types` (
  `id` int(11) NOT NULL,
  `position_type_name` varchar(255) NOT NULL,
  `position_type_order` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `position_types` (`id`, `position_type_name`, `position_type_order`, `status`) VALUES
(1, 'TBD', 0, 0),
(2, 'All Positions', 0, 0),
(3, 'Any Position', 0, 0),
(4, 'Architecture', 0, 0),
(5, 'Barista', 0, 0),
(6, 'Bartender', 0, 0),
(7, 'Biz-Admin/Management/HR', 0, 0),
(8, 'Biz-Communication/PR/Advertising', 0, 0),
(9, 'Biz-Finance/Accounting', 0, 0),
(10, 'Biz-Import/Export', 0, 0),
(11, 'Biz-Marketing/Sales/Promotion', 0, 0),
(12, 'Combined F&B and Front Office ', 0, 0),
(13, 'Conference Services', 0, 0),
(14, 'Conventions/Convention Intern', 0, 0),
(15, 'Culinary/Any', 0, 0),
(16, 'Culinary/Cook', 0, 0),
(17, 'Culinary/Cook III', 0, 0),
(18, 'Culinary/Cook Prep', 0, 0),
(19, 'Culinary/Cuisinier', 0, 0),
(20, 'Culinary/Food Prep', 0, 0),
(21, 'Culinary/kitchen help', 0, 0),
(22, 'Culinary/Kitchen help', 0, 0),
(23, 'Culinary/Kitchen help/Dishwasher', 0, 0),
(24, 'Culinary/Pastry', 0, 0),
(25, 'Culinary/Steward', 0, 0),
(26, 'Culinary/All', 0, 0),
(27, 'Culinary/Cook', 0, 0),
(28, 'F&B/Any', 0, 0),
(29, 'F&B/Assist. Server/Busser', 0, 0),
(30, 'F&B/Banquet', 0, 0),
(31, 'F&B/Bar', 0, 0),
(32, 'F&B/Bar Server', 0, 0),
(33, 'F&B/Bell Attendant', 0, 0),
(34, 'F&B/Buffet Attendant', 0, 0),
(35, 'F&B/Busser/Host/Service', 0, 0),
(36, 'F&B/Coffee Shop Attendant', 0, 0),
(37, 'F&B/Dishwasher/Steward', 0, 0),
(38, 'F&B/Food Runner', 0, 0),
(39, 'F&B/Food Runner ', 0, 0),
(40, 'F&B/Greeter', 0, 0),
(41, 'F&B/Housekeeping', 0, 0),
(42, 'F&B/IRD Server', 0, 0),
(43, 'F&B/Management', 0, 0),
(44, 'F&B/Outlets', 0, 0),
(45, 'F&B/Restaurant Greeter', 0, 0),
(46, 'F&B/Restaurant Server', 0, 0),
(47, 'F&B/Room Service', 0, 0),
(48, 'F&B/Server', 0, 0),
(49, 'F&B/Sommelier', 0, 0),
(50, 'F&B/Supervisor', 0, 0),
(51, 'F&B/Turndown Attendants', 0, 0),
(52, 'F&B/IRD Server', 0, 0),
(53, 'F&B/Servers/Banquets/Housekeeping', 0, 0),
(54, 'Front Desk Agent', 0, 0),
(55, 'Hosp Biz-Admin/Management/HR', 0, 0),
(56, 'Hosp Biz-Finance/Accounting', 0, 0),
(57, 'Hosp Biz-Marketing/Sales/Promotion', 0, 0),
(58, 'Housekeeping/ Supervisor', 0, 0),
(59, 'Human Resources Intern', 0, 0),
(60, 'Housekeeping', 0, 1),
(61, 'Housekeeping/Supervisor', 0, 0),
(62, 'Human Resources Intern', 0, 0),
(63, 'IRD Sales Agent ', 0, 0),
(64, 'IRD Server', 0, 0),
(65, 'IT', 0, 0),
(66, 'Landscaping/Landscaping', 0, 0),
(67, 'Law', 0, 0),
(68, 'Night Auditor', 0, 0),
(69, 'Purchasing Clerk', 0, 0),
(70, 'Purchasing Support', 0, 0),
(71, 'Recreation', 0, 0),
(72, 'Reservation Agent', 0, 0),
(73, 'Reservation Analyst', 0, 0),
(74, 'Reservation Supervisor', 0, 0),
(75, 'Restaurant Consultant', 0, 0),
(76, 'Retail/Shops Intern', 0, 0),
(77, 'Rooms Div/Any', 0, 0),
(78, 'Rooms Div/Front Desk/Service Express', 0, 0),
(79, 'Rooms Div/Front Desk/PBX', 0, 0),
(80, 'Rooms Div/Guest Services', 0, 0),
(81, 'Rooms Div/Guest Services/Bellsevice', 0, 0),
(82, 'Rooms Div/Housekeeping', 0, 0),
(83, 'Rooms Div/Housekeeping Supervisor', 0, 0),
(84, 'Rooms Div/Houseperson', 0, 0),
(85, 'Rooms Div/Laundry Attendant ', 0, 0),
(86, 'Rooms Div/Management', 0, 0),
(87, 'Rooms Div/Recreation/Recreation Att.', 0, 0),
(88, 'Rooms Div/Room Attendants', 0, 0),
(89, 'Rooms Div/Turndown Attendant', 0, 0),
(90, 'Rooms/Front Desk Agent', 0, 0),
(91, 'Rooms/Reservation Analyst', 0, 0),
(92, 'Rooms/Reservation Supervisor', 0, 0),
(93, 'Rooms/Restaurant Consultant', 0, 0),
(94, 'Rotational F&B/HSK ', 0, 0),
(95, 'Security', 0, 0),
(96, 'Ski Valet', 0, 0),
(97, 'Spa Attendant', 0, 0),
(98, 'F&B Any', 0, 1),
(99, 'F&B Management', 0, 1),
(100, 'F&B Bartender', 0, 1),
(101, 'Culinary I (Entry Level - Stewarding)', 0, 1),
(102, 'Culinary II  (Food Prep)', 0, 1),
(103, 'Culinary III  (Line Cook)', 0, 1),
(104, 'Culinary Management', 0, 1),
(105, 'Culinary Pastry', 0, 1),
(106, 'Rooms Division Any', 0, 1),
(107, 'Rooms Division Management', 0, 1),
(108, 'Rooms Division Front Desk', 0, 1),
(109, 'Hospitality Business', 0, 1),
(110, 'Business Candidate', 0, 1),
(111, 'Guest Services', 0, 1),
(112, 'Restaurant Supervisor', 0, 1),
(113, 'Culinary', 0, 0),
(114, 'F&B/Host', 0, 0),
(115, 'F&B/Room Attendant', 0, 0),
(116, 'F&B/Campground Host', 0, 0),
(117, 'PBX Agent', 0, 0),
(118, 'Front Desk', 0, 0),
(119, 'Culiary', 0, 0),
(120, 'Front Office Agent', 0, 0),
(121, 'Front Office Supervisor', 0, 0),
(122, 'Culinary Cook', 0, 0),
(123, 'F&B/ Asst Manager', 0, 0),
(124, 'Guest Services and events', 0, 0),
(125, 'F&B/ Servers', 0, 0),
(126, 'F&B', 0, 0),
(127, 'F&B Front of House', 0, 0),
(128, 'Front Desk / Guest Services', 0, 0),
(129, 'F&B Greeter', 0, 0),
(130, 'F&B Server', 0, 0),
(131, 'Front desk/Night Audit', 0, 0),
(132, 'Rotational program (F&B+FO+Admin)', 0, 0),
(133, 'Doorman', 0, 0),
(134, 'Bellman', 0, 0),
(135, 'Stock Purchasing/Culinary', 0, 0),
(136, 'Front Office', 0, 0),
(137, 'F&B/Pool', 0, 0),
(138, 'F&B/ Server', 0, 0),
(139, 'Cook 1', 0, 0),
(141, 'F&B Any - Mondrian Hotel', 0, 0),
(142, 'Guest Services/Bell', 0, 0),
(143, 'Culinary III (Line Cook)', 0, 0),
(144, 'F&B/Recreation/Guest Services', 0, 0),
(145, 'F&B/Guest Services/Pool', 0, 0),
(146, 'Mesa Verde - Fair View Lodge/F&B', 0, 0),
(147, 'Mesa Verde - Fair View Terrace/Culinary', 0, 0),
(148, 'Mesa Verde - Fair View Lodge/Culinary', 0, 0);

ALTER TABLE `position_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position_type_id` (`id`);

ALTER TABLE `position_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-----------------------------------------------------------------------------------------------
--Hetal Visaveliya (20 January 2020)

UPDATE `application_status_steps` SET `j1_status_id` = '1011' WHERE `application_status_steps`.`id` = 7; 

ALTER TABLE `j1_interview` CHANGE `english_level` `english_level` INT(1) NULL DEFAULT NULL;
ALTER TABLE `j1_interview` CHANGE `preferred_program_length` `preferred_program_length` INT(1) NULL DEFAULT NULL;

UPDATE `programs` SET `status` = '0' WHERE `programs`.`id` = 5;
----------------------------------------------------------------------------------------------------------
-- Manish Bodar(20 January 2020)

ALTER TABLE `admins` ADD `timezone` INT NULL DEFAULT NULL AFTER `password`;

--------------------------------------------------------------------------
-- Manish Bodar(20 January 2020)

ALTER TABLE `faq_master` CHANGE `faq_order` `faq_order` INT(11) NULL DEFAULT '0';

-----------------------------------------------------------------------------------------------
-- Priyank Khunt (23 January 2020)

ALTER TABLE `user_log`
    DROP `action_by_admin`,
    DROP `action_by_user`;

ALTER TABLE `user_log` 
    ADD `action_by_id` INT NULL COMMENT 'reference of users, admins >> id' AFTER `action_status`, 
    ADD `action_by_type` TINYINT NOT NULL DEFAULT '3' COMMENT '1 = admin, 2 = user, 3 = auto admin' AFTER `action_by_id`,
    ADD INDEX (`action_by_id`);

ALTER TABLE `agency_contract` 
    ADD `request_by_id` INT NULL DEFAULT NULL AFTER `request_status`, 
    ADD INDEX (`request_by_id`);
-----------------------------------------------------------------------------------------------
-- Hetal Visaveliya (23 January 2020)

UPDATE `tz_zone` SET `is_active` = '1' WHERE `tz_zone`.`zone_id` = 17;

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES 
(NULL, '11', 'navigatestage', 'Navigate Stage', 'Navigate Application Stage', 'navigatestage', NULL);

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES 
(NULL, '11', 'agency.contract.action', 'Agency Contract Action', 'Agency Contract Action', 'agency.contract.action', NULL);

-----------------------------------------------------------------------------------------------
-- Priyank Khunt (23 January 2020)

UPDATE `application_status_steps` SET `as_icon` = 'mdi mdi-account-card-details' WHERE `application_status_steps`.`id` = 8;

DELETE FROM `j1_statuses` WHERE `j1_statuses`.`id` = 6005;
DELETE FROM `j1_statuses` WHERE `j1_statuses`.`id` = 6006;
DELETE FROM `j1_statuses` WHERE `j1_statuses`.`id` = 6007;

UPDATE `j1_statuses` SET 
    `status_key` = 'contract-request-sent', 
    `status_name` = 'Contract Request' 
WHERE `j1_statuses`.`id` = 6002;

UPDATE `j1_statuses` SET 
    `status_key` = 'contract-accepted',
    `status_name` = 'Contract Accepted' 
WHERE `j1_statuses`.`id` = 6003;

UPDATE `j1_statuses` SET 
    `status_key` = 'contract-rejected' 
    `status_name` = 'Contract Rejected' 
WHERE `j1_statuses`.`id` = 6004;
--------------------------------------------------------------------------
-- Manish Bodar(24 January 2020)

ALTER TABLE `documents` CHANGE `approved_at` `approved_at` TIMESTAMP NULL DEFAULT NULL, CHANGE `rejected_at` `rejected_at` TIMESTAMP NULL DEFAULT NULL;

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '5', 'agency.contract.list', 'Agency Contract', 'Display All Contract List', 'agency.contract.list', '1');

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '5', 'admincontract', 'Filter Contract', 'Filter Contract', 'admincontract', '1');

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '8', 'setcolor', 'Set color', 'Set Theme color', 'setcolor', '1');

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '5', 'accept.contract', 'Accept Contract', 'Accept Contract', 'accept.contract', '0');

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '5', 'reject.contract', 'Reject Contract', 'Reject Contract', 'reject.contract', '0');
--------------------------------------------------------------------------
-- Hetal Visaveliya(24 January 2020)

UPDATE `permissions` SET `route_name` = 'user.contract.action', `permission_name` = 'user.contract.action' , `display_name` = 'User Contract Action', `description` = 'User Contract Action' WHERE `permissions`.`route_name` = 'agency.contract.action';

--------------------------------------------------------------------------
-- Nidhi Lakhani (28 January 2020)
ALTER TABLE `agency_contract` ADD `email` VARCHAR(255) NOT NULL AFTER `portfolio_id`;

--------------------------------------------------------------------------
-- Hetal Visaveliya (28 January 2020)

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'uploaddocument', 'Upload Document', 'Allow to Upload Document', 'uploaddocument', NULL);

--------------------------------------------------------------------------
-- Hetal Visaveliya (03 February 2020)

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'upload.document.instruction', 'Upload Document Instruction', 'How to upload Document Instruction', 'upload.document.instruction', NULL);

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'document.history', 'Document History', 'Upload Document History', 'document.history', NULL);

--------------------------------------------------------------------------
-- Hetal Visaveliya (04 February 2020)
INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'download.document.template', 'Download Document Template', 'Download Document Template', 'download.document.template', NULL);

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'download.document', 'Download Document', 'Download Document', 'download.document', NULL);

--------------------------------------------------------------------------
--Manisha Odedara (05 February 2020)
CREATE TABLE `lead` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `portfolio_id` int(11) DEFAULT NULL,
  `hc_id` int(11) DEFAULT NULL,
  `pos_id` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `lead`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`),
  ADD KEY `portfolio_id` (`portfolio_id`),
  ADD KEY `hc_id` (`hc_id`);

ALTER TABLE `lead`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `placement` (
  `id` int(11) NOT NULL,
  `pos_id` int(11) DEFAULT NULL COMMENT 'host_company_positions-> id',
  `hc_id` int(11) DEFAULT NULL COMMENT 'host_companies->id',
  `portfolio_id` int(11) DEFAULT NULL,
  `salary` varchar(250) DEFAULT NULL,
  `pay_rate_basis` int(11) DEFAULT NULL COMMENT '1=Per Hour,2= Per Day,3=  Per Week,4=Per Month,5=Per Year',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '1=booked,2=placed',
  `pla_order` int(11) DEFAULT NULL COMMENT 'placement order',
  `booked_date` datetime DEFAULT NULL,
  `placed_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `placement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pos_id` (`pos_id`),
  ADD KEY `hc_id` (`hc_id`),
  ADD KEY `portfolio_id` (`portfolio_id`),
  ADD KEY `pla_order` (`pla_order`);

ALTER TABLE `placement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
----------------------------------------------------------------------
-- Hetal Visaveliya (05 February 2020)

UPDATE `application_status_steps` SET `as_icon` = 'fa-file-text-o' WHERE `application_status_steps`.`id` = 9;

UPDATE `application_status_steps` SET `as_icon` = 'fa-edit' WHERE `application_status_steps`.`id` = 8;

UPDATE `application_status_steps` SET `as_icon` = 'fa-search' WHERE `application_status_steps`.`id` = 10;

UPDATE `application_status_steps` SET `as_icon` = 'fa-calendar' WHERE `application_status_steps`.`id` = 11;

UPDATE `application_status_steps` SET `as_icon` = 'fa-download' WHERE `application_status_steps`.`id` = 12;

UPDATE `application_status_steps` SET `as_icon` = 'fa-edit' WHERE `application_status_steps`.`id` = 13;

UPDATE `application_status_steps` SET `as_icon` = 'fa-file-text-o' WHERE `application_status_steps`.`id` = 14;

UPDATE `application_status_steps` SET `as_icon` = 'fa-file-text-o' WHERE `application_status_steps`.`id` = 15;

UPDATE `application_status_steps` SET `as_icon` = 'fa-pencil' WHERE `application_status_steps`.`id` = 16;

UPDATE `application_status_steps` SET `as_icon` = 'fa-pencil' WHERE `application_status_steps`.`id` = 17;

UPDATE `application_status_steps` SET `as_icon` = 'fa-file-text-o' WHERE `application_status_steps`.`id` = 18;

UPDATE `application_status_steps` SET `as_icon` = 'fa-user' WHERE `application_status_steps`.`id` = 19;

UPDATE `application_status_steps` SET `as_icon` = 'fa-cc-visa' WHERE `application_status_steps`.`id` = 20;

UPDATE `application_status_steps` SET `as_icon` = 'fa-plane' WHERE `application_status_steps`.`id` = 21;

UPDATE `application_status_steps` SET `as_icon` = 'fa-home' WHERE `application_status_steps`.`id` = 22;

UPDATE `application_status_steps` SET `as_icon` = 'fa-stethoscope' WHERE `application_status_steps`.`id` = 23;

UPDATE `application_status_steps` SET `as_icon` = 'fa-plane' WHERE `application_status_steps`.`id` = 24;

UPDATE `application_status_steps` SET `as_icon` = 'fa-calendar-check-o' WHERE `application_status_steps`.`id` = 25;

UPDATE `application_status_steps` SET `as_icon` = 'fa-smile-o' WHERE `application_status_steps`.`id` = 26;

UPDATE `application_status_steps` SET `as_icon` = 'fa-smile-o' WHERE `application_status_steps`.`id` = 28;

UPDATE `application_status_steps` SET `as_icon` = 'fa-smile-o' WHERE `application_status_steps`.`id` = 27;

UPDATE `application_status_steps` SET `as_icon` = 'fa-smile-o' WHERE `application_status_steps`.`id` = 29;

----------------------------------------------------------------------
-- Nidhi Lakhani (06 February 2020)

ALTER TABLE `j1_interview` ADD `interview_type` INT(11) NOT NULL AFTER `interview_status`;
ALTER TABLE `j1_interview` CHANGE `interview_type` `interview_type` INT(11) NOT NULL COMMENT '1 = pre-screening, 2 = HC interview';
----------------------------------------------------------------------
--Manisha Odedara (07 February 2020)

CREATE TABLE `legal` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `portfolio_id` int(11) DEFAULT NULL,
  `ds_number` varchar(150) DEFAULT NULL,
  `ds_start_date` date DEFAULT NULL,
  `ds_end_date` date DEFAULT NULL,
  `tracking_number` varchar(150) DEFAULT NULL,
  `ds_shipment_date` date DEFAULT NULL,
  `is_locked` tinyint(4) DEFAULT '0' COMMENT '1=locked,0=Unlocked',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `legal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `portfolio_id` (`portfolio_id`);

ALTER TABLE `legal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

----------------------------------------------------------------------
--Hetal Visaveliya (11 February 2020)
 
INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'user.document', 'User Document', 'User Document', 'user.document', NULL);

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'user.document.list', 'User Document List', 'User Document List', 'user.document.list', NULL);

----------------------------------------------------------------------------------------
-- Manisha Odedara (11 February 2020)

ALTER TABLE `user_details` ADD `embassy_interview` DATETIME NULL DEFAULT NULL AFTER `lock_additional_info`, ADD `embassy_timezone` INT(11) NULL DEFAULT NULL AFTER `embassy_interview`;

------------------------------------------------------------------------------------------------
-- Priyank Khunt (12 February 2020)

DROP TABLE `programs`;

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `program_name` varchar(200) NOT NULL,
  `dos_program_category_id` tinyint(1) NOT NULL COMMENT 'Ref key of dos_program_category table',
  `program_enroll_id` int(11) DEFAULT NULL COMMENT 'programs >> id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Active, 0 = Deactive'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `programs` (`id`, `program_name`, `dos_program_category_id`, `program_enroll_id`, `status`) VALUES
(1, 'Intern - Current Student', 1, NULL, 1),
(2, 'Intern - Recent Graduate', 1, NULL, 1),
(3, 'Trainee - Recent Graduate', 2, NULL, 1),
(4, 'Trainee - Young Professional', 2, NULL, 1),
(5, 'Route 66 / Int - Current Stud', 1, 1, 1),
(6, 'Route 66 / Int - Rec Grad', 1, 2, 1),
(7, 'Route 66 / Trn - Rec Grad', 2, 3, 1),
(8, 'Route 66 / Trn - Young Pro', 2, 4, 1);


UPDATE `j1_statuses` SET `id` = '1012' WHERE `j1_statuses`.`status_key` = 'additional-information-saved';
UPDATE `j1_statuses` SET `id` = '1010' WHERE `j1_statuses`.`status_key` = 'registration-fee-completed';
UPDATE `j1_statuses` SET `id` = '1011' WHERE `j1_statuses`.`status_key` = 'additional-information-saved';


UPDATE `application_status_steps` SET `j1_status_id` = '1010' WHERE `application_status_steps`.`as_order_key` = '1_additional_info';
UPDATE `application_status_steps` SET `j1_status_id` = '1011' WHERE `application_status_steps`.`as_order_key` = '2_contract_placement';

DELETE FROM `permissions` WHERE `permissions`.`permission_name` = 'accept.contract';
DELETE FROM `permissions` WHERE `permissions`.`permission_name` = 'reject.contract';

UPDATE `permissions` SET `route_name` = 'agency.contract.action', `permission_name` = 'agency.contract.action' , `display_name` = 'Agency Contract Action', `description` = 'Agency Contract Action' WHERE `permissions`.`route_name` = 'user.contract.action';
-------------------------------------------------------------------------------------------- 
--Manisha Odedara (12 February 2020)
ALTER TABLE `document_requirements` CHANGE `visibility` `visibility` TINYINT(4) NULL DEFAULT NULL COMMENT '1=admin,2=user,3=both';

--------------------------------------------------------------------------------------------
--Hetal Visaveliya (12 February 2020)

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'document.action', 'Document Action ', 'Document Action [approve, reject, delete]', 'document.action', NULL);

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'doc.uploaded', 'Document Uploaded', 'Required Document Uploaded ', 'doc.uploaded', NULL);

-----------------------------------------------------------------------------------------------
-- Priyank Khunt (13 February 2020)

ALTER TABLE `permissions` ADD `parent_permission_id` INT NULL AFTER `id`, ADD INDEX (`parent_permission_id`);

ALTER TABLE `documents`
    ADD `action_by_id` INT NOT NULL COMMENT 'action (approve,reject) done by admin' AFTER `document_status`,
    ADD INDEX (`action_by_id`);

ALTER TABLE `documents`
  DROP `approved_by_admin`,
  DROP `approved_at`,
  DROP `rejected_by_admin`,
  DROP `rejected_at`;


DELETE FROM `permissions` WHERE `permissions`.`permission_name` = 'agency.contract.action';
DELETE FROM `permissions` WHERE `permissions`.`permission_name` = 'agency.contract.list';
DELETE FROM `permissions` WHERE `permissions`.`permission_name` = 'admincontract';

UPDATE `permissions` SET `is_menu_item` = '0' WHERE `permissions`.`permission_name` = 'setcolor';

INSERT INTO `permissions` (`permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES
(11, 'agency.contract.list', 'Agency Contract', 'Display All Contract List', 'agency.contract.list', 1),
(11, 'agency.contract.action', 'Agency Contract Action', 'Agency Contract Action', 'agency.contract.action', NULL),
(11, 'agency.filter.contract', 'Filter Contract', 'Filter Contract', 'agency.filter.contract', NULL);

--------------------------------------------------------------------------------------------
--Hetal Visaveliya (12 February 2020)

INSERT INTO `permissions` (`id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, '6', 'uploadresume', 'Upload Resume', 'Upload Resume', 'uploadresume', NULL), (NULL, '6', 'uploadagreement', 'Upload Agreement', 'Upload Agreement', 'uploadagreement', NULL);

------------------------------------------------------------------------------------------------ 
--Manisha Odedara (13 February 2020)

ALTER TABLE `user_details` ADD `visa_denied_count` TINYINT NULL DEFAULT NULL AFTER `embassy_timezone`;
ALTER TABLE `user_details` ADD `consecutive_visa_denied_flag` TINYINT NULL DEFAULT NULL COMMENT '0=Not Eligible, 1=Lock, 2=Unlock, 3=Eligible' AFTER `visa_denied_count`;

INSERT INTO `j1_statuses` (`id`, `status_key`, `status_name`, `category`) VALUES
(3010, '221g-letter-received-green-form', '221(g) Letter received (Green Form)', 3),
(3011, 'under-administrative-processing', 'Under Administrative Processing', 3),
(3012, 'visa-denied-embassy-lock', 'Visa Denied Embassy Lock', 3),
(3013, 'visa-denied-quit-program', 'Visa Denied - Quit Program', 3);

-----------------------------------------------------------------------------------------------------
--Manisha Odedara (18 February 2020)
CREATE TABLE `flight_info` (
  `id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL COMMENT 'users -> id',
  `portfolio_id` int(11) DEFAULT NULL COMMENT 'portfolio-> id',
  `departure_timezone` varchar(255) DEFAULT NULL,
  `departure_date` datetime DEFAULT NULL,
  `departure_airport` varchar(5) DEFAULT NULL,
  `arrival_airport` varchar(5) DEFAULT NULL,
  `airline` varchar(255) DEFAULT NULL,
  `flight` varchar(255) DEFAULT NULL,
  `arrival_timezone` varchar(255) DEFAULT NULL,
  `arrival_date` datetime DEFAULT NULL,
  `additional_info` mediumtext,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `flight_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `portfolio_id` (`portfolio_id`);

ALTER TABLE `flight_info`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;COMMIT;

-----------------------------------------------------------------------------------------------
-- Priyank Khunt (19 February 2020)

UPDATE `application_status_steps` SET `as_order` = '2' WHERE `application_status_steps`.`as_order_key` = '3_post_placement_documents';

ALTER TABLE `documents` ADD `placement_id` INT NULL COMMENT 'reference of placement >> id' AFTER `portfolio_id`, ADD INDEX (`placement_id`);
-------------------------------------------------------------------------------------------------------------
-- Manisha Odedara (20 February 2020)

INSERT INTO `j1_statuses` (`id`, `status_key`, `status_name`, `category`) VALUES
(6005, 'not-selected-by-host-company', 'Not Selected by Host Company', 6),
(6006, 'rejected-by-host-company', 'Rejected by Host Company', 6),
(6007, 'interview-refused-by-candidate', 'Interview refused by candidate', 6),
(6008, 'training-position-no-longer-opened', 'Training Position No Longer Opened', 6);

INSERT INTO `permissions` (`id`, `parent_permission_id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, NULL, '11', 'add.lead', 'Add Lead', 'Add New Lead for Candidates', 'add.lead', '0');

-------------------------------------------------------------------------------------------------------------
-- Hetal Visaveliya (26 February 2020)

ALTER TABLE `host_company_positions` CHANGE `pos_description` `pos_description` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE `host_company_positions` CHANGE `no_of_openings` `no_of_openings` SMALLINT(6) NULL;

ALTER TABLE `host_company_positions` CHANGE `salary` `salary` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE `host_company_positions` CHANGE `pay_rate_basis` `pay_rate_basis` TINYINT(1) NULL COMMENT '1 = Per Hour, 2 = Per Day, 3 = Per Week, 4 = Per Month, 5 = Per Year';

ALTER TABLE `host_company_positions` CHANGE `tips` `tips` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE `host_company_positions` CHANGE `housing_description` `housing_description` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

--------------------------------------------------------------------------------------------------------------------------
--Manisha Odedara (27 February 2020)

INSERT INTO `permissions` (`id`, `parent_permission_id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, NULL, '11', 'hiring.stage', 'Hiring Stage', 'Hiring Stage', 'hiring.stage', '0');
INSERT INTO `permissions` (`id`, `parent_permission_id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, NULL, '11', 'visa.stage', 'Visa Stage', 'Visa Stage', 'visa.stage', '0');

ALTER TABLE `resume_education` CHANGE `minor` `minor` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `resume_education` CHANGE `description` `description` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE `placement` CHANGE `start_date` `start_date` DATE NULL DEFAULT NULL, CHANGE `end_date` `end_date` DATE NULL DEFAULT NULL;
ALTER TABLE `placement` ADD `date` DATETIME NULL DEFAULT NULL AFTER `pla_order`;
ALTER TABLE `placement` DROP `booked_date`, DROP `placed_date`;
---------------------------------------------------------------------------------------------------------------
--Nidhi Lakhani (03 March 2020)

ALTER TABLE `users` ADD `has_notification` TINYINT(4) NOT NULL COMMENT '0 = hsa not unread notification, 1 = has unread notification' AFTER `remember_token`;
---------------------------------------------------------------------------------------------------------------
-- Manisha Odedara (05 March 2020)

ALTER TABLE `placement` ADD `booked_date` DATETIME NULL DEFAULT NULL AFTER `date`, ADD `placed_date` DATETIME NULL DEFAULT NULL AFTER `booked_date`;
ALTER TABLE `placement` DROP `date`;

---------------------------------------------------------------------------------------------------------------
-- Hetal Visaveliya (05 March 2020)

INSERT INTO `permissions` (`permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES ('8', 'admin.search', 'Admin Search', 'Admin Search', 'admin.search', '0');

-----------------------------------------------------------------------------------------------------------------
-- Manisha Odedara (06 March 2020)
INSERT INTO `permissions` (`id`, `parent_permission_id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, NULL, '6', 'dr.ajax', 'Document requirements ajax call', 'Hiring Stage ajax call', 'dr.ajax', '0');
INSERT INTO `permissions` (`id`, `parent_permission_id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, NULL, '10', 'hc.detail', 'Visa Stage', 'Host Company Detail', 'hc.detail', '0');
INSERT INTO `permissions` (`id`, `parent_permission_id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, NULL, '10', 'hc.ajax', 'Host Company ajax call', 'Host Company ajax call', 'hc.ajax', '0');
INSERT INTO `permissions` (`id`, `parent_permission_id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, NULL, '11', 'user.ajax', 'User ajax call', 'User ajax call', 'user.ajax', '0');

ALTER TABLE `users` ADD `status` TINYINT NULL DEFAULT '1' COMMENT '0=De Active,2=Active' AFTER `email_verified`;
-------------------------------------------------------------------------------------------------------------------
--Manisha Odedara (09 March 2020)

TRUNCATE TABLE `application_status_steps`;

INSERT INTO `application_status_steps` (`id`, `as_stage_number`, `as_order_key`, `as_order`, `as_title`, `as_icon`, `as_desc_before`, `as_desc_current`, `as_desc_after`, `j1_status_id`) VALUES
(1, 1, '1_eligibility_test', 1, 'Eligibility Test', 'fa-check-square-o', '', '', 'The purpose of the eligibility test is to determine which J1 visa sub-category you ', '1001,1002'),
(2, 1, '1_resume_upload', 2, 'Resume Upload', 'fa-file-text-o', 'Upload a resume to impress your Host Companies. This is your chance to catch your Host Companies attention. If you don\' have a resume, we will offer you a free resume builder.', 'Upload a resume to impress your Host Companies. This is your chance to catch your Host Companies attention. If you don\' have a resume, we will offer you a free resume builder.', 'Your Resume has been submitted successfully.', '1003'),
(3, 1, '1_resume_approval', 3, 'J1 Resume Approval', 'fa-file-text', 'J1 agent will review and evaluate your submitted resume and you will be notified within 24 hours.', 'J1 agent will review and evaluate your submitted resume and you will be notified within 24 hours.', 'The purpose of our pre-screening interview is to evaluate your English level and gather additional information regarding your application. ', '1004'),
(4, 1, '1_skype', 4, 'Your Skype', 'fa-skype', 'The interview will take place using Skype.\r\nWe will offer a download link. ', 'The interview will take place using Skype.\r\nWe will offer a download link.', 'The interview will take place using Skype.\r\nWe will offer a download link.', '1005'),
(5, 1, '1_j1_interview', 5, 'J1 Interview', 'fa-user', 'Pre-screening interview with an J1 agent to evaluate your English level and gather additional information regarding your application.', 'Pre-screening interview with an J1 agent to evaluate your English level and gather additional information regarding your application.', 'Pre-screening interview with an J1 agent to evaluate your English level and gather additional information regarding your application.', '1006,1007'),
(6, 1, '1_j1_agreement', 6, 'J1 Agreement', 'fa-file-text-o', 'You will need to sign the J1 Agreement in order to continue our process.', 'You will need to sign the J1 Agreement in order to continue our process.', 'You will need to sign the J1 Agreement in order to continue our process.', '1008'),
(7, 1, '1_additional_info', 8, 'Additional Information', 'fa-info-circle', '', 'This is where we ask you to provide a few more pieces of information, if needed.', 'This is where we ask you to provide a few more pieces of information, if needed.', '1010'),
(8, 2, '2_contract_placement', 1, 'Contract With Placement Agency', 'fa-edit', '', '', '', '1011'),
(9, 2, '2_supporting_documents', 2, 'Supporting Documents', 'fa-file-text-o', 'We need certain documents based on the specific requirements for the visa type which you will use to enter the U.S.', 'We need certain documents based on the specific requirements for the visa type which you will use to enter the U.S.', 'We need certain documents based on the specific requirements for the visa type which you will use to enter the U.S.', '2001'),
(10, 2, '2_searching_position', 3, 'Searching Position', 'fa-search', 'This step can take some time so please be patient, we will contact you as soon as we have a lead on a suitable position for you. ', 'This step can take some time so please be patient, we will contact you as soon as we have a lead on a suitable position for you. ', 'This step can take some time so please be patient, we will contact you as soon as we have a lead on a suitable position for you. ', '2002'),
(11, 2, '2_booked', 4, 'Booked Position', 'fa-calendar', '\"Booking\" means that an Host Company has shown interest in interviewing you!', '\"Booking\" means that an Host Company has shown interest in interviewing you!', '\"Booking\" means that an Host Company has shown interest in interviewing you!', '2003'),
(12, 2, '2_placed', 5, 'Placed Position', 'fa-download', 'This where we confirm when the Host Company is ready to hire you.', 'This where we confirm when the Host Company is ready to hire you.', 'This where we confirm when the Host Company is ready to hire you.', '2004'),
(13, 3, '3_contract_sponsor', 1, 'Contract With Sponsor Agency', 'fa-edit', '', '', '', '2005'),
(14, 3, '3_post_placement_documents', 2, 'Post placement documents as per sponsor', 'fa-file-text-o', 'We need certain documents based on the specific requirements for the visa type which you will use to enter the U.S.', 'We need certain documents based on the specific requirements for the visa type which you will use to enter the U.S.', 'We need certain documents based on the specific requirements for the visa type which you will use to enter the U.S.', '3001'),
(15, 3, '3_ds7002_pending', 3, 'DS7002 Pending', 'fa-file-text-o', 'The DS7002 Form will be send to your Host Company.', 'The DS7002 Form will be send to your Host Company.', 'The DS7002 Form will be send to your Host Company.', '3002'),
(16, 3, '3_ds7002_created', 4, 'DS7002 Created', 'fa-pencil', 'At this step you are expected to sign the DS7002 Form, the form was sent to your registered address.', 'At this step you are expected to sign the DS7002 Form, the form was sent to your registered address.', 'At this step you are expected to sign the DS7002 Form, the form was sent to your registered address.', '3003'),
(17, 3, '3_ds7002_signed', 5, 'DS7002 Signed', 'fa-pencil', 'At this step you are expected to sign the DS7002 Form, the form was sent to your registered address.', 'At this step you are expected to sign the DS7002 Form, the form was sent to your registered address.', 'At this step you are expected to sign the DS7002 Form, the form was sent to your registered address.', '3004'),
(18, 3, '3_ds2019_sent', 6, 'DS2019 Sent', 'fa-file-text-o', 'You will receive the DS2019 Form to presented in your US embassy interview.', 'You will receive the DS2019 Form to presented in your US embassy interview.', 'You will receive the DS2019 Form to presented in your US embassy interview.', '3005'),
(19, 3, '3_us_embassy_interview', 7, 'US Embassy Interview', 'fa-user', '', '', '', '3006'),
(20, 3, '3_us_visa_outcome', 8, 'US Visa Outcome', 'fa-cc-visa', '', '', '', '3007'),
(21, 3, '3_flight_info', 9, 'Flight Info', 'fa-plane', 'Information about your flight.', 'Information about your flight.', 'Information about your flight.', '3008'),
(22, 3, '3_arrival_in_usa', 10, 'Arrival In USA', 'fa-home', 'Your final step is to arrive in the US and begin your life-changing experience!', 'Your final step is to arrive in the US and begin your life-changing experience!', 'Your final step is to arrive in the US and begin your life-changing experience!', '4001'),
(23, 4, '4_medical_insurance', 1, 'Medical Insurance', 'fa-stethoscope', '', '', '', ''),
(24, 4, '4_arrival_check_in', 2, 'Arrival Check In', 'fa-plane', '', '', '', ''),
(25, 4, '4_monthly_check_in', 3, 'Monthly Check In', 'fa-calendar-check-o', '', '', '', ''),
(26, 4, '4_mid_super_evaluation', 4, 'Review Your Midterm Supervisor Evaluation', 'fa-smile-o', '', '', '', ''),
(27, 4, '4_mid_evaluation', 5, 'Midterm Evaluation', 'fa-smile-o', '', '', '', ''),
(28, 4, '4_final_super_evaluation', 6, 'Review Your Final Supervisor Evaluation', 'fa-smile-o', '', '', '', ''),
(29, 4, '4_final_evaluation', 7, 'Final Evaluation', 'fa-smile-o', '', '', '', ''),
(30, 1, '1_registration_fee', 7, 'Registration Fee', 'fa-dollar', '50 USD/EUR non-refundable registration fee. This is a one time, non-refundable fee for the processing of your application.', '50 USD/EUR non-refundable registration fee. This is a one time, non-refundable fee for the processing of your application.', '50 USD/EUR non-refundable registration fee. This is a one time, non-refundable fee for the processing of your application.', '1009');
----------------------------------------------------------------------------------------------------------------------
--Manisha Odedara (13 March 2020)

ALTER TABLE `programs` ADD `category` INT(11) NULL DEFAULT NULL COMMENT 'program_category->id' AFTER `program_enroll_id`;
UPDATE `programs` SET `category` = '3' WHERE `programs`.`id` = 5; UPDATE `programs` SET `category` = '3' WHERE `programs`.`id` = 6; UPDATE `programs` SET `category` = '3' WHERE `programs`.`id` = 7; UPDATE `programs` SET `category` = '3' WHERE `programs`.`id` = 8;

CREATE TABLE `program_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0=deactive,1=active',
  `category_order` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `program_category` (`id`, `name`, `status`, `category_order`) VALUES
(1, 'J1', 1, 0),
(2, 'Work and Travel', 1, 0),
(3, 'Route 66', 1, 0),
(4, 'Placement Only', 1, 0),
(5, 'Filing Only', 1, 0),
(6, 'Other', 1, 0),
(7, 'Vatel', 0, 0);

ALTER TABLE `program_category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `program_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--------------------------------------------------------------------------------------
--Manisha Odedara (17 March 2020)

INSERT INTO `permissions` (`id`, `parent_permission_id`, `permission_group_id`, `permission_name`, `display_name`, `description`, `route_name`, `is_menu_item`) VALUES (NULL, NULL, '6', 'dr.search', 'Document Requirement search', 'Document Requirement search', 'dr.search', '0');
--------------------------------------------------------------------------------------------