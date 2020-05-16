<?php

return [
  
    'auto_admin' => 9999,
    'allow_image_ext' => ["jpg", "jpeg", "png"],
    'allow_doc_ext' => ["pdf", "doc", "docx"],
    'allow_pdf' => ["pdf"],
    'upload_file_size' => 10,
    'upload_img_size' => 2,
    'page_limit' => "5",
    'no_avatar' => "assets/images/noavatar.png",
    'no_image' => "assets/images/noimage.png",
    
    'user-documents' => 'user-documents',
    'remember_me_timeout' => "20160", /* Set 2 weeks minute (14 * 24 * 60) */
     
    /** Start Common Email Settings **/ 
    'from_name' => "",
    'no_reply_email' => "",
    'query_break_email' => [
        'to' => ['itnqaemail@gmail.com'],
        'cc' => [
            'ravi'       => 'ravi.r1.php@gmail.com',
            'ravi_raval' => 'ravi@odcitservices.com',
            'chirag'     => 'chirag@odcitservices.com',
            'priyank'    => 'priyank@odcitservices.com', 
            'darshan'    => 'darshan@odcitservices.com',
            'kashmeera'  => 'kashmeera@odcitservices.com',
            'brijesh'    => 'brijesh@odcitservices.com',
            'hetal'      => 'hetal@odcitservices.com',
            'manisha'    => 'manisha@odcitservices.com',
            'nidhi'      => 'nidhi@odcitservices.com',
        ],
    ],
    
    'paypal_settings' => [
        'paypal_action_url' => 'https://www.paypal.com/cgi-bin/webscr',
        'hosted_button_id' => '',
        'enable_sandbox' => false,
    ],
    
    'setting_section' => [
        'common_setting' => 'Common Settings',
        'social_setting' => 'Social Settings',
        'password_setting' => 'Password Settings',
        'clear_cache' => 'Clear Cache'
    ],  
      
    'hc_status' => [
        'name' => [
            1 => "Active",
            2 => "Deactive",
            3 => "Under Review",
        ],
        'class' => [
            1 => "success",
            2 => "danger",
            3 => "warning",
        ],
    ],
    
    'pay_rate_basis' => [
        1 => "Per Hour",
        2 => "Per Day",
        3 => "Per Week",
        4 => "Per Month",
        5 => "Per Year",
    ],
    
    'agency_type' => [
        '1' => 'Registration',
        '2' => 'Placement',
        '3' => 'Sponsor',
        '4' => 'General'
    ],
    
    'document_section' => [
        '1' => 'Registration Section',
        '2' => 'Sponsor Section',
        '3' => 'Embassy Section'
    ],
    
    
    'application_status_stages' => [
        '1' => [
            "stage_id" => 1,
            "stage_title" => "Registration Stage",
            "sidebar_title" => "Registration Steps",
            "page_title" => "Registration with J1",
            "page_subtitle" => "Our journey starts here...",
            "procedure_subtitle" => "Initiating Visa application process",
            "stage_key" => "registration",
            "timeline_partner_type" =>  1,
            "stage_description" =>  "Once you are registered, you will have to face eligibility test. On qualifying the test you will be allotted your J1 program. After its completion you will have to go through Pre-screening Interview. Once you get through, you will sign J1 agreement.",
        ],
        '2' => [
            "stage_id" => 2,
            "stage_title" => "Hiring Stage",
            "sidebar_title" => "Placement Steps",
            "page_title" => "Host Company Placement",
            "page_subtitle" => "the search has begun...",
            "procedure_subtitle" => "Placement With a U.S. Host Company",
            "stage_key" => "placement",
            "timeline_partner_type" =>  1,
            "stage_description" =>  "It is exclusively incumbent upon us to search for the vacancy as per your program and then your placement. Once your placement is Okayed a sponsor will be provided.",
        ],
        '3' => [
            "stage_id" => 3,
            "stage_title" => "Visa Stage",
            "sidebar_title" => "Visa Steps",
            "page_title" => "Visa Application",
            "page_subtitle" => "The first step towards freedom...",
            "procedure_subtitle" => "U.S. Visa Application",
            "stage_key" => "us_visa_application",
            "timeline_partner_type" =>  1,
            "stage_description" =>  "Your allotted Sponsor will seek your visa as per the prescribed norms of the company and you will have to show up for the Embassy Interview only. Once your Visa is approved you will have to inform us about arrival so that we can provide you medical insurance.",
        ],
        '4' => [
            "stage_id" => 4,
            "stage_title" => "After Arrival in USA Stage",
            "sidebar_title" => "After Arrival in USA Steps",
            "page_title" => "After Arrival in USA",
            "page_subtitle" => "",
            "procedure_subtitle" => "",
            "stage_key" => "after_arrival",
            "timeline_partner_type" =>  2,
            "stage_description" =>  "It is our prime duty to see you back after accomplishing your program without any inconvenience. It is pertinent to mention over here that you have to be very circumspect with regard to your correspondence (i.e. Arrival Check-In, Monthly Check-In, Midterm and Final Evaluation) with your sponsor.",
        ],
    ],
 
    'eligibility_quest' => [
        0 => [
            'question' => "What industry are you interested in?",
            'options' => [
                1 => ['label' => "Hospitality, Tourism & Culinary",'answer' => 1],
                2 => ['label' => "Business Management",'answer' => 2],
                3 => ['label' => "IT & Systems",'answer' => 3],
                4 => ['label' => "Engineering",'answer' => 4],
                5 => ['label' => "Human Science (Philosophy, Sociology, politics etc)",'answer' => 5],
            ],
        ],
        101 => [
            'question' => "How old are you?",
            'options' => [
                1 => ['label' => "Less than 18 years",'answer' => 105],
                2 => ['label' => "Between 18 and 35 years",'answer' => 103],
                3 => ['label' => "More than 35 years",'answer' => 105],
            ],
        ],
        103 => [
            'question' => "Do you have a post-secondary degree in Hospitality, Tourism or Culinary Arts?",
            'options' => [
                1 => ['label' => "Yes",'answer' => 106],
                2 => ['label' => "No",'answer' => 107],
            ],
            'desc' => "<p class=\"text-muted m-t-10\"><i class=\"fa fa-exclamation-circle fa-2x m-r-5\"></i>Post-secondary degree refers to any education beyond high school</p>",
        ],
        105 => [
            'result' => "error",
            'desc' => "Unfortunately, you are not eligible for any of our current programs. You can <span class=\"text-info\">retake the eligibility test</span> again if any of your information were changed.<br><br>
                Meanwhile, check our forums for tips on how to improve your chances to be eligible for coming to the United States:<br><br>
                <a href=\"#\">How to Successfully Get Your J-1 Student Visa</a><br>
                <a href=\"#\">How to Apply for a J-1 Visa</a><br>
                <a href=\"#\">IMPROVE YOUR CHANCES OF BEING ISSUED A VISA</a><br>
                <a href=\"#\">Common Questions - 'application_term.exchange_visitor' Visa</a>"
        ],
        106 => [
            'question' => "When did you graduate?",
            'options' => [
                1 => ['label' => "Less than one year",'answer' => 108],
                2 => ['label' => "More than one year",'answer' => 109],
                3 => ['label' => "Other",'answer' => 109],
            ],
        ],
        107 => [
            'question' => "Are you currently enrolled in a post-secondary degree program studying one of these specialty: <i>Hospitality, Tourism or Culinary</i> ?",
            'options' => [
                1 => ['label' => "Yes",'answer' => 110],
                2 => ['label' => "No",'answer' => 111],
            ],
            'desc' => "<p class=\"text-muted m-t-10\"><i class=\"fa fa-exclamation-circle fa-2x m-r-5\"></i>Post-secondary degree refers to any education beyond high school</p>"
        ],
        108 => [
            'result' => "success",
            'program' => 2,
            'desc' => "You have successfully completed the eligibility test and you are eligible to apply as a <strong>J1 Intern</strong> in the Category <strong>Recent Graduate</strong>."
        ],
        109 => [
            "question" => "<label>How many years of work experience do you have in your field of training (Hospitality, Tourism or Culinary)?</label>",
            "options"=>[
                1 => ['label' => "Less than one year",'answer' => 105],
                2 => ['label' => "One year or more",'answer' => 108],
            ],
            'desc' => "<p class=\"text-muted m-t-10\"><i class=\"fa fa-exclamation-circle fa-2x m-r-5\"></i>Work experience refers to full time jobs</p>"
        ],
        110 => [
            'result' => "success",
            'program' => 1,
            'desc' => "You are eligible to apply as a:<br><strong>J1 Intern</strong> in the category <strong>Current Student</strong> (up to 12 months) 
                <br>or<br> <strong>J1 Summer Intern</strong> in the category <strong>Work & Travel</strong> (up to 4 months)."
        ],
        111 => [
            'question' => "Are you currently a student in a different field of study: Law, Business, Engineer, ...?",
            'options' => [
                1 => ['label' => "Yes",'answer' => 112],
                2 => ['label' => "No",'answer' => 113],
            ],
        ],
        112 => [
            'result' => "success",
            'program' => 5,
            'desc' => "You are eligible to apply as a <strong>J1 Summer Intern</strong> in the category <strong>Work & Travel</strong> (up to 4 months)."
        ],
        113 => [
            'question' => "How many years of work experience do you have in the Hospitality, Tourism and Culinary Industry?",
            'options' => [
                1 => ['label' => "Less than five years",'answer' => 105],
                2 => ['label' => "Five years or more",'answer' => 114],
            ],
            'desc' => "<p class=\"text-muted m-t-10\"><i class=\"fa fa-exclamation-circle m-r-5\"></i>Work experience referrs to full time jobs.</p>"
        ],
        114 => [
            'result' => "success",
            'program' => 4,
            'desc' => "You have successfully completed the eligibility test and you are eligible to apply as a <strong>J1 Trainee</strong> in the Category \"Young Professional\"."
        ],
    ],
     
    'eligible_industries' => [
        1 => "Hospitality, Tourism & Culinary",
        2 => "Business Management",
        3 => "IT & Systems",
        4 => "Engineering",
        5 => "Human Science (Philosophy, Sociology, politics etc)",
    ],
    
    'relation' => [
        1 => "Student",
        2 => "Parent",
        3 => "Spouse or Partner",
        4 => "Child",
        5 => "Sibling",
        6 => "Other",
        7 => "Teacher",
        8 => "Friend",
        9 => "School Official",
        10 => "Reference",
        11 => "Spouse",
        12 => "Host",
        13 => "Step Parent",
    ],
    
    'institution_type' => [
        1 => "High/Secondary School",
        2 => "University/College",
        3 => "Vocational Training",
    ],
    'study_level' => [
        1 => "Bachelors Degree",
        2 => "Masters Degree",
        3 => "Doctorate",
        4 => "Baccalaureate",
        5 => "Diploma",
        6 => "Associates Degree",
        7 => "Postgraduate",
        8 => "Certificate",
        9 => "Other",
    ],
    
    'program_length' => [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
    ],
        
    'english_level' => [
        1 => [
            'title' => "1 - Can understand and use familiar everyday...",
            'desc' => "Can understand and use familiar everyday expressions and very basic phrases aimed at the satisfaction of needs of a concrete type. Can introduce himself/herself and others and can ask and answer questions about personal details such as where he/she lives, people he/she knows, and things he/she has. Can interact in a simple way provided the other person talks slowly and clearly and is prepared to help. Little communication may be possible outside of the realms described above. Speech may be intelligible."
        ], 
        2 => [
            'title' => "2 - Can understand frequently used expressions...",
            'desc' => "Can understand frequently used expressions related to areas of most immediate relevance, such as very basic personal and family information, shopping, local geography, and employment. Can communicate in simple and routine tasks requiring a simple and direct exchange of information on familiar and routine matters. Can describe in simple terms aspects of his/her background, immediate environment, and matters in areas of immediate need. May be able to convey only basic meanings often characterized by long pauses. Mispronunciations may cause difficulty for the interlocutor."
        ],
        3 => [
            'title' => "3 - Can understand the main points of clear...",
            'desc' => "Can understand the main points of clear standard input on familiar matters regularly encountered in work, school, and leisure. Can deal with most situations likely to arise in an area where the language is spoken. Can produce simple connected text on topics that are familiar or of personal interest. Can describe experiences and events, dreams, hopes, and ambitions and can briefly give reasons and explanations for opinions and plans. The user at this level has mastered the basic structures of the language and is beginning to attempt to produce more complex language."
        ],
        4 => [
            'title' => "4 - Can understand concrete and abstract topics,...",
            'desc' => "Can understand concrete and abstract topics, including technical discussions in his/her field of specialization. Can interact with a degree of fluency and spontaneity that makes regular interaction with native speakers quite possible without strain for either party. Can produce clear, detailed speech on a wide range of subjects and explain a viewpoint on a topical issue. Can speak at length, but may show hesitation or exhibit a lack of coherence. May use vocabulary and grammatical structures with limited flexibility. Can usually be understood but has a limited range of pronunciation features"
        ],
        5 => [
            'title' => "5 - Can recognize implicit meaning. Can express...",
            'desc' => "Can recognize implicit meaning. Can express himself/herself fluently and spontaneously without much obvious searching for expressions and only occasional repetition. Can use language flexibly and effectively for social, academic, and professional purposes. Can produce clear, well-structured, detailed text on complex subjects, showing controlled use of organizational patterns, connectors, and cohesive devices. Can use vocabulary flexibly with some occasional inappropriate wording."
        ],
        6 => [
            'title' => "6 - Can understand with ease virtually everything...",
            'desc' => "Can understand with ease virtually everything heard. Can summarize information in a coherent presentation. Can express himself/herself spontaneously, very fluently and precisely, differentiating finer shades of meaning even in more complex situations. Can use vocabulary flexibly and precisely. Can appropriately and naturally use grammatical structures. Pronunciation is precise and poses no problems for the interlocutor."
        ],
    ],
    'pay_rate_basis' => [
        1 => "Per Hour",
        2 => "Per Day",
        3 => " Per Week",
        4 => "Per Month",
        5 => "Per Year",
    ],
    
];
