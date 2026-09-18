<?php

    use Illuminate\Support\Facades\Route;



    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\StudentsController;
    use App\Http\Controllers\ExamsController;

    //Sourav Start
    use App\Http\Controllers\AdmissionController;
    use App\Http\Controllers\PasswordController;
    use App\Http\Controllers\ResultController;
    use App\Http\Controllers\Exam_givenController;
    use App\Http\Controllers\NoticesController;
    use App\Http\Controllers\Upcoming_examController;
    use App\Http\Controllers\Exam_homeController;
    use App\Http\Controllers\Common_confusionController;

    //Sourav End

    use App\Http\Controllers\Admin\AuthController;
    use App\Http\Controllers\Admin\AdminController;
    use App\Http\Controllers\Admin\ProfileController;
    use App\Http\Controllers\Admin\DashboardController;
    use App\Http\Controllers\Admin\Dashboard_bannerController;
    use App\Http\Controllers\Admin\Dashboard_contentController;
    use App\Http\Controllers\Admin\CountryController;
    use App\Http\Controllers\Admin\StateController;
    use App\Http\Controllers\Admin\DistrictController;
    use App\Http\Controllers\Admin\CityController;
    use App\Http\Controllers\Admin\LocationController;
    use App\Http\Controllers\Admin\RankerController;
    use App\Http\Controllers\Admin\RankerRulesController;
    use App\Http\Controllers\Admin\RankerFeedbackController;
    use App\Http\Controllers\Admin\RankerAppointmentController;
    use App\Http\Controllers\Admin\RankerAssignController;
    use App\Http\Controllers\Admin\RankerPriceController;

    use App\Http\Controllers\Admin\StudentController;
    use App\Http\Controllers\Admin\PaymentController;

    use App\Http\Controllers\Admin\Offline_examController;
    use App\Http\Controllers\Admin\Offline_exam_resultController;
    use App\Http\Controllers\Admin\Online_examController;
    use App\Http\Controllers\Admin\Exam_locationController;
    use App\Http\Controllers\Admin\CommonController;

    use App\Http\Controllers\Admin\SubjectController;
    use App\Http\Controllers\Admin\ChapterController;
    use App\Http\Controllers\Admin\TopicController;
    use App\Http\Controllers\Admin\Sub_topicController;
    use App\Http\Controllers\Admin\SourceController;
    use App\Http\Controllers\Admin\Question_typeController;
    use App\Http\Controllers\Admin\Question_sourceController;
    use App\Http\Controllers\Admin\QuestionController;
    use App\Http\Controllers\Admin\Question_paperController;

    use App\Http\Controllers\Admin\CouponController;
    use App\Http\Controllers\Admin\CourseController;

    use App\Http\Controllers\Admin\Admin_userController;
    use App\Http\Controllers\Admin\TeacherController;
    use App\Http\Controllers\Admin\Admin_user_roleController;
    use App\Http\Controllers\Admin\CounsellorController;

    use App\Http\Controllers\Admin\FooterController;
    use App\Http\Controllers\Admin\SubscriptionController;
    use App\Http\Controllers\Admin\ContactController;
    use App\Http\Controllers\Admin\ProblemController;
    use App\Http\Controllers\Admin\Problem_typeController;
    use App\Http\Controllers\Admin\Video_tutorialController;
    use App\Http\Controllers\Admin\CmsController;
    use App\Http\Controllers\Admin\NoticeController;


    //Sourav Start
    use App\Http\Controllers\Admin\BannerController;
    use App\Http\Controllers\Admin\ScholarshipController;
    use App\Http\Controllers\Admin\TestSeriesController;
    use App\Http\Controllers\Admin\InterestController;
    use App\Http\Controllers\Admin\HeadQuaterController;
    use App\Http\Controllers\Admin\RealStoryController;
    use App\Http\Controllers\Admin\SuccessStoryController;
    use App\Http\Controllers\Admin\HurryNowController;
    use App\Http\Controllers\Admin\LearningController;
    use App\Http\Controllers\Admin\AskedQuestionController;
    use App\Http\Controllers\Admin\GalleryController;
    use App\Http\Controllers\Admin\MentionController;

    //Sourav End

    // Counsellor Start

    use App\Http\Controllers\Counsellor\CounsellorLoginController;
    use App\Http\Controllers\Counsellor\CounsellorUserController;
    use App\Http\Controllers\Counsellor\CounsellorDashboardController;
    use App\Http\Controllers\Counsellor\CounsellorProfileController;

    // Counsellor End

    Route::get('/', [HomeController::class, 'index'])->name('index');
    
    Route::get('/blank', [ResultController::class, 'blank'])->name('blank');
    Route::get('/blank_two', [ResultController::class, 'blank_two'])->name('blank_two');
    Route::get('/blank_three', [ResultController::class, 'blank_three'])->name('blank_three');
    Route::get('/blank_four', [ResultController::class, 'blank_four'])->name('blank_four');
    Route::get('/blank_five', [ResultController::class, 'blank_five'])->name('blank_five');

    Route::get('/air', [ResultController::class, 'air'])->name('air');

    Route::get('/notice', [NoticesController::class, 'notice'])->name('notice')->middleware(['verifyMembership']);
    Route::get('/notice/favorite', [NoticesController::class, 'notice_favorite'])->name('notice.favorite')->middleware(['verifyMembership']);
    Route::get('/notice/archive', [NoticesController::class, 'notice_archive'])->name('notice.archive')->middleware(['verifyMembership']);
    Route::get('/notice/delete', [NoticesController::class, 'notice_delete'])->name('notice.delete')->middleware(['verifyMembership']);

    Route::get('/upcoming-exam', [Upcoming_examController::class, 'upcoming_exam'])->name('upcoming_exam')->middleware(['verifyMembership']);
    Route::get('/upcoming-exam/accept', [Upcoming_examController::class, 'upcoming_exam_accept'])->name('upcoming_exam.accept')->middleware(['verifyMembership']);
    Route::get('/upcoming-exam/reject', [Upcoming_examController::class, 'upcoming_exam_reject'])->name('upcoming_exam.reject')->middleware(['verifyMembership']);

    Route::post('/downloadPdf', [Exam_givenController::class, 'downloadPdf'])->name('downloadPdf');

    Route::get('/leader-board', [Exam_givenController::class, 'leader_board'])->name('leader_board')->middleware(['verifyMembership']);

    Route::get('/exam-given', [Exam_givenController::class, 'exam_given'])->name('exam_given')->middleware(['verifyMembership']);
    Route::get('/exam-result-detail', [Exam_givenController::class, 'exam_result_detail'])->name('exam_result_detail')->middleware(['verifyMembership']);

    Route::get('/mistake-monitor-input', [Exam_givenController::class, 'mistake_monitor_input'])->name('mistake_monitor_input')->middleware(['verifyMembership']);
    Route::get('/mistake-monitor-input-reason', [Exam_givenController::class, 'mistake_monitor_input_reason'])->name('mistake_monitor_input_reason')->middleware(['verifyMembership']);
    Route::get('/mistake-monitor-output', [Exam_givenController::class, 'mistake_monitor_output'])->name('mistake_monitor_output')->middleware(['verifyMembership']);

    Route::get('/exam-answers-analytics', [Exam_givenController::class, 'answers_analytics'])->name('exam_answers_analytics')->middleware(['verifyMembership']);

    Route::get('/exam-strength', [Exam_givenController::class, 'strength'])->name('exam_strength')->middleware(['verifyMembership']);
    Route::get('/exam-strength/topics', [Exam_givenController::class, 'strength_topics'])->name('exam_strength.topics')->middleware(['verifyMembership']);
    Route::get('/exam-strength/subtopics', [Exam_givenController::class, 'strength_subtopics'])->name('exam_strength.subtopics')->middleware(['verifyMembership']);
    Route::get('/exam-strength/question', [Exam_givenController::class, 'strength_question'])->name('exam_strength.question')->middleware(['verifyMembership']);

    Route::get('/exam-weakness', [Exam_givenController::class, 'weakness'])->name('exam_weakness')->middleware(['verifyMembership']);
    Route::get('/exam-weakness/topics', [Exam_givenController::class, 'weakness_topics'])->name('exam_weakness.topics')->middleware(['verifyMembership']);
    Route::get('/exam-weakness/subtopics', [Exam_givenController::class, 'weakness_subtopics'])->name('exam_weakness.subtopics')->middleware(['verifyMembership']);
    Route::get('/exam-weakness/question', [Exam_givenController::class, 'weakness_question'])->name('exam_weakness.question')->middleware(['verifyMembership']);

    Route::get('/exam-progress-report', [Exam_givenController::class, 'progress_report'])->name('exam_progress_report')->middleware(['verifyMembership']);
    Route::get('/exam-personal-coach', [Exam_givenController::class, 'personal_coach'])->name('exam_personal_coach')->middleware(['verifyMembership']);

    Route::get('/answers-analytics', [Exam_homeController::class, 'answers_analytics'])->name('answers_analytics')->middleware(['verifyMembership']);

    Route::get('/strength', [Exam_homeController::class, 'strength'])->name('strength')->middleware(['verifyMembership']);
    Route::get('/strength/topics', [Exam_homeController::class, 'strength_topics'])->name('strength.topics')->middleware(['verifyMembership']);
    Route::get('/strength/subtopics', [Exam_homeController::class, 'strength_subtopics'])->name('strength.subtopics')->middleware(['verifyMembership']);
    Route::get('/strength/question', [Exam_homeController::class, 'strength_question'])->name('strength.question')->middleware(['verifyMembership']);

    Route::get('/weakness', [Exam_homeController::class, 'weakness'])->name('weakness')->middleware(['verifyMembership']);
    Route::get('/weakness/topics', [Exam_homeController::class, 'weakness_topics'])->name('weakness.topics')->middleware(['verifyMembership']);
    Route::get('/weakness/subtopics', [Exam_homeController::class, 'weakness_subtopics'])->name('weakness.subtopics')->middleware(['verifyMembership']);
    Route::get('/weakness/question', [Exam_homeController::class, 'weakness_question'])->name('weakness.question')->middleware(['verifyMembership']);

    Route::get('/progress-report', [Exam_homeController::class, 'progress_report'])->name('progress_report')->middleware(['verifyMembership']);
    Route::get('/personal-coach', [Exam_homeController::class, 'personal_coach'])->name('personal_coach')->middleware(['verifyMembership']);

    Route::get('/trending-exam', [ResultController::class, 'trending_exam'])->name('trending_exam')->middleware(['verifyMembership']);

    Route::get('/common_confusion', [Common_confusionController::class, 'common_confusion'])->name('common_confusion')->middleware(['verifyMembership']);
    Route::get('/common_confusion/topics', [Common_confusionController::class, 'common_confusion_topics'])->name('common_confusion.topics')->middleware(['verifyMembership']);
    Route::get('/common_confusion/subtopics', [Common_confusionController::class, 'common_confusion_subtopics'])->name('common_confusion.subtopics')->middleware(['verifyMembership']);
    Route::get('/common_confusion/question', [Common_confusionController::class, 'common_confusion_question'])->name('common_confusion.question')->middleware(['verifyMembership']);
    
    Route::get('/save-subscription', [HomeController::class, 'save_subscription'])->name('save_subscription');


    Route::get('/online-exam', [ExamsController::class, 'online_exam'])->name('online_exam')->middleware(['verifyMembership']);
    Route::get('/start-exam', [ExamsController::class, 'start_exam'])->name('start_exam')->middleware(['verifyMembership']);
    Route::get('/start-online-exam', [ExamsController::class, 'start_online_exam'])->name('start_online_exam')->middleware(['verifyMembership']);
    Route::post('/save-exam', [ExamsController::class, 'save_exam'])->name('save_exam')->middleware(['verifyMembership']);
    Route::post('/end-exam', [ExamsController::class, 'end_exam'])->name('end_exam')->middleware(['verifyMembership']);
    Route::post('/update-exam-time', [ExamsController::class, 'update_exam_time'])->name('update_exam_time')->middleware(['verifyMembership']);
    Route::post('/update-user-exam-question', [ExamsController::class, 'update_user_exam_question'])->name('update_user_exam_question')->middleware(['verifyMembership']);

    Route::get('/dashboard', [StudentsController::class, 'dashboard'])->name('dashboard')->middleware(['verifyMembership']);
    Route::get('/dashboard-exam-type', [StudentsController::class, 'dashboard_exam_type'])->name('dashboard_exam_type')->middleware(['verifyMembership']);
    Route::get('/strong-areas', [StudentsController::class, 'strong_areas'])->name('strong_areas')->middleware(['verifyMembership']);
    Route::get('/can-improve', [StudentsController::class, 'can_improve'])->name('can_improve')->middleware(['verifyMembership']);
    Route::get('/need-to-work-hard', [StudentsController::class, 'need_to_work_hard'])->name('need_to_work_hard')->middleware(['verifyMembership']);
    Route::get('/profile', [StudentsController::class, 'profile'])->name('profile')->middleware(['verifyMembership']);
    Route::post('/update-profile', [StudentsController::class, 'update_profile'])->name('update_profile')->middleware(['verifyMembership']);
    Route::get('/my-courses', [StudentsController::class, 'my_courses'])->name('my_courses')->middleware(['verifyMembership']);
    Route::get('/schedule', [StudentsController::class, 'schedule'])->name('schedule')->middleware(['verifyMembership']);
    Route::get('/result', [StudentsController::class, 'result'])->name('result')->middleware(['verifyMembership']);
    Route::get('/rankers-for-rankers', [StudentsController::class, 'rankers_for_rankers'])->name('rankers_for_rankers')->middleware(['verifyMembership']);
    Route::get('/plans', [StudentsController::class, 'plans'])->name('plans')->middleware(['verifyMembership']);
    Route::get('/terms-and-condition', [StudentsController::class, 'terms_and_condition'])->name('terms_and_condition')->middleware(['verifyMembership']);
    Route::get('/report-problem', [StudentsController::class, 'report_problem'])->name('report_problem')->middleware(['verifyMembership']);
    Route::post('/report-problem-save', [StudentsController::class, 'report_problem_save'])->name('report_problem_save')->middleware(['verifyMembership']);
    Route::get('/logout', [StudentsController::class, 'logout'])->name('logout')->middleware(['verifyMembership']);


    Route::get('/new-light', [HomeController::class, 'new_light'])->name('new_light');
    Route::get('/new-light/{id}', [HomeController::class, 'new_light_detail'])->name('new_light.details');
    Route::post('/new-light-checkout', [HomeController::class, 'new_lightCheckout'])->name('new_light.checkout')->middleware(['verifyMembership']);

    Route::get('/testseries', [HomeController::class, 'testseries'])->name('testseries');
    Route::get('/testseries-details/{id}', [HomeController::class, 'testSeriesDetails'])->name('testseries.details');
    Route::get('/testseries-checkout', [HomeController::class, 'testSeriesCheckout'])->name('testseries.checkout')->middleware(['verifyMembership']);
    Route::post('/testseries-save-checkout', [HomeController::class, 'testSeriesSaveCheckout'])->name('testseries.save_checkout')->middleware(['verifyMembership']);
    Route::post('/testseries-coupon', [HomeController::class, 'checkCoupon'])->name('testseries.coupon');
    Route::get('/ranker', [HomeController::class, 'ranker'])->name('ranker');
    Route::get('/ranker-detail/{id}', [HomeController::class, 'ranker_detail'])->name('ranker.detail');
    Route::get('/ranker-checkout/{id}', [HomeController::class, 'rankerCheckout'])->name('ranker.checkout')->middleware(['verifyMembership']);
    Route::post('/ranker-save-checkout', [HomeController::class, 'rankerSaveCheckout'])->name('ranker.save_checkout')->middleware(['verifyMembership']);

    //Sourav Start
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::post('/save_contact', [HomeController::class, 'save_contact'])->name('save_contact');

    Route::get('/signup', [AdmissionController::class, 'signup'])->name('signup');
    Route::post('/admissionstore', [AdmissionController::class, 'store'])->name('admissionstore');
    Route::post('/verifyOtp', [AdmissionController::class, 'verifyOtp'])->name('verifyOtp');
    Route::post('/resend-otp', [AdmissionController::class, 'resendOtp'])->name('resendOtp');
    Route::get('/login', [AdmissionController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdmissionController::class, 'login'])->name('login.submit');

    Route::get('forgot-password', [PasswordController::class, 'showForgotPasswordForm'])->name('password.forgot');
    Route::post('forgot-password', [PasswordController::class, 'sendVerificationCode'])->name('password.sendCode');
    Route::get('verify-code', [PasswordController::class, 'showVerificationForm'])->name('password.verify');
    Route::post('verify-code', [PasswordController::class, 'verifyCode'])->name('password.verifyCode');
    Route::get('reset-password', [PasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');
    //Sourav End


    Route::get('webadmin', [AuthController::class, 'index'])->name('admin.webadmin');
    Route::get('admin', [AuthController::class, 'index'])->name('admin.index');
    Route::post('admin/dologin', [AuthController::class, 'dologin'])->name('admin.dologin');
    Route::get('admin/forgot-password', [AuthController::class, 'forgot_password'])->name('admin.forgot_password');
    Route::post('admin/update-forgot-password', [AuthController::class, 'updateforgotpassword'])->name('admin.updateforgotpassword');
    Route::get('admin/reset-password/{id}', [AuthController::class, 'reset_password'])->name('admin.reset_password');
    Route::post('admin/update-reset-password', [AuthController::class, 'updateresetpassword'])->name('admin.updateresetpassword');

    Route::name('admin.')
    ->prefix('admin')
    ->middleware(['adminAuth'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [AdminController::class, 'index'])->name('profile');
        Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('update_profile');
        Route::get('/profile/change-password', [AdminController::class, 'changePassword'])->name('change_password');
        Route::post('/profile/update-password', [AdminController::class, 'updatePassword'])->name('updatePassword');
        Route::get('/profile/setting', [AdminController::class, 'setting'])->name('setting');
        Route::get('/profile/edit-setting', [AdminController::class, 'editSetting'])->name('editSetting');
        Route::post('/profile/update-setting', [AdminController::class, 'updateSetting'])->name('updateSetting');
        Route::get('/profile/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/admin-user', [Admin_userController::class, 'list'])->name('admin_user');
        Route::get('/admin-user/add', [Admin_userController::class, 'add'])->name('admin_user.add');
        Route::post('/admin-user/save', [Admin_userController::class, 'save'])->name('admin_user.save');
        Route::get('/admin-user/edit', [Admin_userController::class, 'edit'])->name('admin_user.edit');
        Route::post('/admin-user/update', [Admin_userController::class, 'update'])->name('admin_user.update');
        Route::get('/admin-user/delete', [Admin_userController::class, 'delete'])->name('admin_user.delete');
        Route::get('/admin-user/change', [Admin_userController::class, 'change'])->name('admin_user.change');

        Route::get('/video_tutorial', [Video_tutorialController::class, 'list'])->name('video_tutorial');
        Route::get('/video_tutorial/add', [Video_tutorialController::class, 'add'])->name('video_tutorial.add');
        Route::post('/video_tutorial/save', [Video_tutorialController::class, 'save'])->name('video_tutorial.save');
        Route::get('/video_tutorial/edit', [Video_tutorialController::class, 'edit'])->name('video_tutorial.edit');
        Route::post('/video_tutorial/update', [Video_tutorialController::class, 'update'])->name('video_tutorial.update');
        Route::get('/video_tutorial/delete', [Video_tutorialController::class, 'delete'])->name('video_tutorial.delete');
        Route::get('/video_tutorial/change', [Video_tutorialController::class, 'change'])->name('video_tutorial.change');

        Route::get('/cms', [CmsController::class, 'list'])->name('cms');
        Route::get('/cms/add', [CmsController::class, 'add'])->name('cms.add');
        Route::post('/cms/save', [CmsController::class, 'save'])->name('cms.save');
        Route::get('/cms/edit', [CmsController::class, 'edit'])->name('cms.edit');
        Route::post('/cms/update', [CmsController::class, 'update'])->name('cms.update');
        Route::get('/cms/delete', [CmsController::class, 'delete'])->name('cms.delete');
        Route::get('/cms/change', [CmsController::class, 'change'])->name('cms.change');

        Route::get('/question_type', [Question_typeController::class, 'list'])->name('question_type');
        Route::get('/question_type/add', [Question_typeController::class, 'add'])->name('question_type.add');
        Route::post('/question_type/save', [Question_typeController::class, 'save'])->name('question_type.save');
        Route::get('/question_type/edit', [Question_typeController::class, 'edit'])->name('question_type.edit');
        Route::post('/question_type/update', [Question_typeController::class, 'update'])->name('question_type.update');
        Route::get('/question_type/delete', [Question_typeController::class, 'delete'])->name('question_type.delete');
        Route::get('/question_type/change', [Question_typeController::class, 'change'])->name('question_type.change');

        Route::get('/dashboard-banner', [Dashboard_bannerController::class, 'list'])->name('dashboard_banner');
        Route::get('/dashboard-banner/add', [Dashboard_bannerController::class, 'add'])->name('dashboard_banner.add');
        Route::post('/dashboard-banner/save', [Dashboard_bannerController::class, 'save'])->name('dashboard_banner.save');
        Route::get('/dashboard-banner/edit', [Dashboard_bannerController::class, 'edit'])->name('dashboard_banner.edit');
        Route::post('/dashboard-banner/update', [Dashboard_bannerController::class, 'update'])->name('dashboard_banner.update');
        Route::get('/dashboard-banner/delete', [Dashboard_bannerController::class, 'delete'])->name('dashboard_banner.delete');
        Route::get('/dashboard-banner/change', [Dashboard_bannerController::class, 'change'])->name('dashboard_banner.change');

        Route::get('/dashboard-content', [Dashboard_contentController::class, 'list'])->name('dashboard_content');
        Route::post('/dashboard-content/update', [Dashboard_contentController::class, 'update'])->name('dashboard_content.update');

        Route::get('/notice', [NoticeController::class, 'list'])->name('notice');
        Route::get('/notice/add', [NoticeController::class, 'add'])->name('notice.add');
        Route::post('/notice/save', [NoticeController::class, 'save'])->name('notice.save');
        Route::get('/notice/edit', [NoticeController::class, 'edit'])->name('notice.edit');
        Route::post('/notice/update', [NoticeController::class, 'update'])->name('notice.update');
        Route::get('/notice/delete', [NoticeController::class, 'delete'])->name('notice.delete');
        Route::get('/notice/change', [NoticeController::class, 'change'])->name('notice.change');
        Route::get('/notice/view', [NoticeController::class, 'view'])->name('notice.view');

        Route::get('/teacher', [TeacherController::class, 'list'])->name('teacher');
        Route::get('/teacher/add', [TeacherController::class, 'add'])->name('teacher.add');
        Route::post('/teacher/save', [TeacherController::class, 'save'])->name('teacher.save');
        Route::get('/teacher/edit', [TeacherController::class, 'edit'])->name('teacher.edit');
        Route::post('/teacher/update', [TeacherController::class, 'update'])->name('teacher.update');
        Route::get('/teacher/delete', [TeacherController::class, 'delete'])->name('teacher.delete');
        Route::get('/teacher/change', [TeacherController::class, 'change'])->name('teacher.change');

        Route::get('/admin-user-role', [Admin_user_roleController::class, 'list'])->name('admin_user_role');
        Route::get('/admin-user-role/add', [Admin_user_roleController::class, 'add'])->name('admin_user_role.add');
        Route::post('/admin-user-role/save', [Admin_user_roleController::class, 'save'])->name('admin_user_role.save');
        Route::get('/admin-user-role/edit', [Admin_user_roleController::class, 'edit'])->name('admin_user_role.edit');
        Route::post('/admin-user-role/update', [Admin_user_roleController::class, 'update'])->name('admin_user_role.update');
        Route::get('/admin-user-role/delete', [Admin_user_roleController::class, 'delete'])->name('admin_user_role.delete');
        Route::get('/admin-user-role/change', [Admin_user_roleController::class, 'change'])->name('admin_user_role.change');

        Route::get('/payment/test_series', [PaymentController::class, 'test_series'])->name('payment.test_series');
        Route::get('/payment/test_series_detail', [PaymentController::class, 'test_series_detail'])->name('payment.test_series_detail');
        Route::get('/payment/ranker', [PaymentController::class, 'ranker'])->name('payment.ranker');
        Route::get('/payment/ranker_detail', [PaymentController::class, 'ranker_detail'])->name('payment.ranker_detail');
        Route::get('/payment/new_light', [PaymentController::class, 'new_light'])->name('payment.new_light');
        Route::get('/payment/new_light_detail', [PaymentController::class, 'new_light_detail'])->name('payment.new_light_detail');

        Route::get('/subscription', [SubscriptionController::class, 'list'])->name('subscription');
        Route::get('/subscription/delete', [SubscriptionController::class, 'delete'])->name('subscription.delete');

        Route::get('/counsellor', [CounsellorController::class, 'list'])->name('counsellor');
        Route::get('/counsellor/add', [CounsellorController::class, 'add'])->name('counsellor.add');
        Route::post('/counsellor/save', [CounsellorController::class, 'save'])->name('counsellor.save');
        Route::get('/counsellor/edit', [CounsellorController::class, 'edit'])->name('counsellor.edit');
        Route::post('/counsellor/update', [CounsellorController::class, 'update'])->name('counsellor.update');
        Route::get('/counsellor/delete', [CounsellorController::class, 'delete'])->name('counsellor.delete');
        Route::get('/counsellor/change', [CounsellorController::class, 'change'])->name('counsellor.change');

        Route::get('/contact', [ContactController::class, 'list'])->name('contact');
        Route::get('/contact/add', [ContactController::class, 'add'])->name('contact.add');
        Route::post('/contact/save', [ContactController::class, 'save'])->name('contact.save');
        Route::get('/contact/edit', [ContactController::class, 'edit'])->name('contact.edit');
        Route::post('/contact/update', [ContactController::class, 'update'])->name('contact.update');
        Route::get('/contact/delete', [ContactController::class, 'delete'])->name('contact.delete');
        Route::get('/contact/change', [ContactController::class, 'change'])->name('contact.change');

        Route::get('/problem_type', [Problem_typeController::class, 'list'])->name('problem_type');
        Route::get('/problem_type/add', [Problem_typeController::class, 'add'])->name('problem_type.add');
        Route::post('/problem_type/save', [Problem_typeController::class, 'save'])->name('problem_type.save');
        Route::get('/problem_type/edit', [Problem_typeController::class, 'edit'])->name('problem_type.edit');
        Route::post('/problem_type/update', [Problem_typeController::class, 'update'])->name('problem_type.update');
        Route::get('/problem_type/delete', [Problem_typeController::class, 'delete'])->name('problem_type.delete');
        Route::get('/problem_type/change', [Problem_typeController::class, 'change'])->name('problem_type.change');

        Route::get('/problem', [ProblemController::class, 'list'])->name('problem');
        Route::get('/problem/add', [ProblemController::class, 'add'])->name('problem.add');
        Route::post('/problem/save', [ProblemController::class, 'save'])->name('problem.save');
        Route::get('/problem/edit', [ProblemController::class, 'edit'])->name('problem.edit');
        Route::post('/problem/update', [ProblemController::class, 'update'])->name('problem.update');
        Route::get('/problem/delete', [ProblemController::class, 'delete'])->name('problem.delete');
        Route::get('/problem/change', [ProblemController::class, 'change'])->name('problem.change');

        Route::get('/coupon', [CouponController::class, 'list'])->name('coupon');
        Route::get('/coupon/add', [CouponController::class, 'add'])->name('coupon.add');
        Route::post('/coupon/save', [CouponController::class, 'save'])->name('coupon.save');
        Route::get('/coupon/edit', [CouponController::class, 'edit'])->name('coupon.edit');
        Route::post('/coupon/update', [CouponController::class, 'update'])->name('coupon.update');
        Route::get('/coupon/delete', [CouponController::class, 'delete'])->name('coupon.delete');
        Route::get('/coupon/change', [CouponController::class, 'change'])->name('coupon.change');

        Route::get('/course', [CourseController::class, 'list'])->name('course');
        Route::get('/course/add', [CourseController::class, 'add'])->name('course.add');
        Route::post('/course/save', [CourseController::class, 'save'])->name('course.save');
        Route::get('/course/edit', [CourseController::class, 'edit'])->name('course.edit');
        Route::post('/course/update', [CourseController::class, 'update'])->name('course.update');
        Route::get('/course/delete', [CourseController::class, 'delete'])->name('course.delete');
        Route::get('/course/change', [CourseController::class, 'change'])->name('course.change');

        Route::get('/student', [StudentController::class, 'list'])->name('student');
        Route::get('/student/add', [StudentController::class, 'add'])->name('student.add');
        Route::post('/student/save', [StudentController::class, 'save'])->name('student.save');
        Route::get('/student/edit', [StudentController::class, 'edit'])->name('student.edit');
        Route::post('/student/update', [StudentController::class, 'update'])->name('student.update');
        Route::get('/student/delete', [StudentController::class, 'delete'])->name('student.delete');
        Route::get('/student/change', [StudentController::class, 'change'])->name('student.change');

        Route::get('/question', [QuestionController::class, 'list'])->name('question');
        Route::get('/question/add', [QuestionController::class, 'add'])->name('question.add');
        Route::post('/question/save', [QuestionController::class, 'save'])->name('question.save');
        Route::get('/question/edit', [QuestionController::class, 'edit'])->name('question.edit');
        Route::post('/question/update', [QuestionController::class, 'update'])->name('question.update');
        Route::get('/question/delete', [QuestionController::class, 'delete'])->name('question.delete');
        Route::get('/question/change', [QuestionController::class, 'change'])->name('question.change');

        Route::get('/question-upload', [QuestionController::class, 'upload'])->name('question.upload');
        Route::post('/question-upload/save', [QuestionController::class, 'upload_save'])->name('question.upload_save');

        Route::get('/chapter', [ChapterController::class, 'list'])->name('chapter');
        Route::get('/chapter/add', [ChapterController::class, 'add'])->name('chapter.add');
        Route::post('/chapter/save', [ChapterController::class, 'save'])->name('chapter.save');
        Route::get('/chapter/edit', [ChapterController::class, 'edit'])->name('chapter.edit');
        Route::post('/chapter/update', [ChapterController::class, 'update'])->name('chapter.update');
        Route::get('/chapter/delete', [ChapterController::class, 'delete'])->name('chapter.delete');
        Route::get('/chapter/change', [ChapterController::class, 'change'])->name('chapter.change');

        Route::get('/topic', [TopicController::class, 'list'])->name('topic');
        Route::get('/topic/add', [TopicController::class, 'add'])->name('topic.add');
        Route::post('/topic/save', [TopicController::class, 'save'])->name('topic.save');
        Route::get('/topic/edit', [TopicController::class, 'edit'])->name('topic.edit');
        Route::post('/topic/update', [TopicController::class, 'update'])->name('topic.update');
        Route::get('/topic/delete', [TopicController::class, 'delete'])->name('topic.delete');
        Route::get('/topic/change', [TopicController::class, 'change'])->name('topic.change');

        Route::get('/sub-topic', [Sub_topicController::class, 'list'])->name('sub_topic');
        Route::get('/sub-topic/add', [Sub_topicController::class, 'add'])->name('sub_topic.add');
        Route::post('/sub-topic/save', [Sub_topicController::class, 'save'])->name('sub_topic.save');
        Route::get('/sub-topic/edit', [Sub_topicController::class, 'edit'])->name('sub_topic.edit');
        Route::post('/sub-topic/update', [Sub_topicController::class, 'update'])->name('sub_topic.update');
        Route::get('/sub-topic/delete', [Sub_topicController::class, 'delete'])->name('sub_topic.delete');
        Route::get('/sub-topic/change', [Sub_topicController::class, 'change'])->name('sub_topic.change');

        Route::get('/subject', [SubjectController::class, 'list'])->name('subject');
        Route::get('/subject/add', [SubjectController::class, 'add'])->name('subject.add');
        Route::post('/subject/save', [SubjectController::class, 'save'])->name('subject.save');
        Route::get('/subject/edit', [SubjectController::class, 'edit'])->name('subject.edit');
        Route::post('/subject/update', [SubjectController::class, 'update'])->name('subject.update');
        Route::get('/subject/delete', [SubjectController::class, 'delete'])->name('subject.delete');
        Route::get('/subject/change', [SubjectController::class, 'change'])->name('subject.change');

        Route::get('/source', [SourceController::class, 'list'])->name('source');
        Route::get('/source/add', [SourceController::class, 'add'])->name('source.add');
        Route::post('/source/save', [SourceController::class, 'save'])->name('source.save');
        Route::get('/source/edit', [SourceController::class, 'edit'])->name('source.edit');
        Route::post('/source/update', [SourceController::class, 'update'])->name('source.update');
        Route::get('/source/delete', [SourceController::class, 'delete'])->name('source.delete');
        Route::get('/source/change', [SourceController::class, 'change'])->name('source.change');

        Route::get('/question-source', [Question_sourceController::class, 'list'])->name('question_source');
        Route::get('/question-source/add', [Question_sourceController::class, 'add'])->name('question_source.add');
        Route::post('/question-source/save', [Question_sourceController::class, 'save'])->name('question_source.save');
        Route::get('/question-source/edit', [Question_sourceController::class, 'edit'])->name('question_source.edit');
        Route::post('/question-source/update', [Question_sourceController::class, 'update'])->name('question_source.update');
        Route::get('/question-source/delete', [Question_sourceController::class, 'delete'])->name('question_source.delete');
        Route::get('/question-source/change', [Question_sourceController::class, 'change'])->name('question_source.change');

        Route::get('/question-paper', [Question_paperController::class, 'list'])->name('question_paper');
        Route::get('/question-paper/add', [Question_paperController::class, 'add'])->name('question_paper.add');
        Route::post('/question-paper/save', [Question_paperController::class, 'save'])->name('question_paper.save');
        Route::get('/question-paper/edit', [Question_paperController::class, 'edit'])->name('question_paper.edit');
        Route::post('/question-paper/update', [Question_paperController::class, 'update'])->name('question_paper.update');
        Route::get('/question-paper/delete', [Question_paperController::class, 'delete'])->name('question_paper.delete');
        Route::get('/question-paper/change', [Question_paperController::class, 'change'])->name('question_paper.change');
        Route::get('/question-paper/view', [Question_paperController::class, 'view'])->name('question_paper.view');
        Route::get('/question-paper/question', [Question_paperController::class, 'question'])->name('question_paper.question');
        Route::get('/question-paper/download-question', [Question_paperController::class, 'download_question'])->name('question_paper.download_question');
        Route::get('/question-paper/download-answer', [Question_paperController::class, 'download_answer'])->name('question_paper.download_answer');
        Route::get('/question-paper/change-question', [Question_paperController::class, 'change_question'])->name('question_paper.change_question');

        Route::get('/offline_exam', [Offline_examController::class, 'list'])->name('offline_exam');
        Route::get('/offline_exam/add', [Offline_examController::class, 'add'])->name('offline_exam.add');
        Route::post('/offline_exam/save', [Offline_examController::class, 'save'])->name('offline_exam.save');
        Route::get('/offline_exam/edit', [Offline_examController::class, 'edit'])->name('offline_exam.edit');
        Route::post('/offline_exam/update', [Offline_examController::class, 'update'])->name('offline_exam.update');
        Route::get('/offline_exam/delete', [Offline_examController::class, 'delete'])->name('offline_exam.delete');
        Route::get('/offline_exam/change', [Offline_examController::class, 'change'])->name('offline_exam.change');
        Route::get('/offline_exam/view', [Offline_examController::class, 'view'])->name('offline_exam.view');
        Route::get('/offline_exam/end', [Offline_examController::class, 'end'])->name('offline_exam.end');
        Route::get('/offline_exam/question', [Offline_examController::class, 'question'])->name('offline_exam.question');
        Route::get('/offline_exam/download-question', [Offline_examController::class, 'download_question'])->name('offline_exam.download_question');
        Route::get('/offline_exam/download-answer', [Offline_examController::class, 'download_answer'])->name('offline_exam.download_answer');
        Route::get('/offline_exam/result', [Offline_examController::class, 'result'])->name('offline_exam.result');
        Route::get('/offline_exam/result_details', [Offline_examController::class, 'result_details'])->name('offline_exam.result_details');
        Route::get('/offline_exam/change-question', [Offline_examController::class, 'change_question'])->name('offline_exam.change_question');

        Route::get('/offline_exam_result/question', [Offline_exam_resultController::class, 'question'])->name('offline_exam_result.question');
        Route::post('/offline_exam_result/question-save', [Offline_exam_resultController::class, 'question_save'])->name('offline_exam_result.question_save');

        
        Route::get('/offline_exam_result/upload', [Offline_exam_resultController::class, 'upload'])->name('offline_exam_result.upload');
        Route::post('/offline_exam_result/upload-save', [Offline_exam_resultController::class, 'upload_save'])->name('offline_exam_result.upload_save');

        Route::get('/online_exam', [Online_examController::class, 'list'])->name('online_exam');
        Route::get('/online_exam/add', [Online_examController::class, 'add'])->name('online_exam.add');
        Route::post('/online_exam/save', [Online_examController::class, 'save'])->name('online_exam.save');
        Route::get('/online_exam/edit', [Online_examController::class, 'edit'])->name('online_exam.edit');
        Route::post('/online_exam/update', [Online_examController::class, 'update'])->name('online_exam.update');
        Route::get('/online_exam/delete', [Online_examController::class, 'delete'])->name('online_exam.delete');
        Route::get('/online_exam/change', [Online_examController::class, 'change'])->name('online_exam.change');
        Route::get('/online_exam/end', [Online_examController::class, 'end'])->name('online_exam.end');
        Route::get('/online_exam/question', [Online_examController::class, 'question'])->name('online_exam.question');
        Route::get('/online_exam/result', [Online_examController::class, 'result'])->name('online_exam.result');
        Route::get('/online_exam/result_details', [Online_examController::class, 'result_details'])->name('online_exam.result_details');

        Route::get('/ranker', [RankerController::class, 'list'])->name('ranker');
        Route::get('/ranker/add', [RankerController::class, 'add'])->name('ranker.add');
        Route::post('/ranker/save', [RankerController::class, 'save'])->name('ranker.save');
        Route::get('/ranker/edit', [RankerController::class, 'edit'])->name('ranker.edit');
        Route::post('/ranker/update', [RankerController::class, 'update'])->name('ranker.update');
        Route::get('/ranker/delete', [RankerController::class, 'delete'])->name('ranker.delete');
        Route::get('/ranker/change', [RankerController::class, 'change'])->name('ranker.change');

        Route::get('/ranker_rule', [RankerRulesController::class, 'list'])->name('ranker_rule');
        Route::get('/ranker_rule/add', [RankerRulesController::class, 'add'])->name('ranker_rule.add');
        Route::post('/ranker_rule/save', [RankerRulesController::class, 'save'])->name('ranker_rule.save');
        Route::get('/ranker_rule/edit', [RankerRulesController::class, 'edit'])->name('ranker_rule.edit');
        Route::post('/ranker_rule/update', [RankerRulesController::class, 'update'])->name('ranker_rule.update');
        Route::get('/ranker_rule/delete', [RankerRulesController::class, 'delete'])->name('ranker_rule.delete');
        Route::get('/ranker_rule/change', [RankerRulesController::class, 'change'])->name('ranker_rule.change');

        Route::get('/ranker_feedback', [RankerFeedbackController::class, 'list'])->name('ranker_feedback');
        Route::get('/ranker_feedback/add', [RankerFeedbackController::class, 'add'])->name('ranker_feedback.add');
        Route::post('/ranker_feedback/save', [RankerFeedbackController::class, 'save'])->name('ranker_feedback.save');
        Route::get('/ranker_feedback/edit', [RankerFeedbackController::class, 'edit'])->name('ranker_feedback.edit');
        Route::post('/ranker_feedback/update', [RankerFeedbackController::class, 'update'])->name('ranker_feedback.update');
        Route::get('/ranker_feedback/delete', [RankerFeedbackController::class, 'delete'])->name('ranker_feedback.delete');
        Route::get('/ranker_feedback/change', [RankerFeedbackController::class, 'change'])->name('ranker_feedback.change');

        Route::get('/ranker_appointment', [RankerAppointmentController::class, 'list'])->name('ranker_appointment');
        Route::get('/ranker_appointment/add', [RankerAppointmentController::class, 'add'])->name('ranker_appointment.add');
        Route::post('/ranker_appointment/save', [RankerAppointmentController::class, 'save'])->name('ranker_appointment.save');
        Route::get('/ranker_appointment/edit', [RankerAppointmentController::class, 'edit'])->name('ranker_appointment.edit');
        Route::post('/ranker_appointment/update', [RankerAppointmentController::class, 'update'])->name('ranker_appointment.update');
        Route::get('/ranker_appointment/delete', [RankerAppointmentController::class, 'delete'])->name('ranker_appointment.delete');
        Route::get('/ranker_appointment/change', [RankerAppointmentController::class, 'change'])->name('ranker_appointment.change');

        Route::get('/ranker_assign', [RankerAssignController::class, 'list'])->name('ranker_assign');
        Route::get('/ranker_assign/add', [RankerAssignController::class, 'add'])->name('ranker_assign.add');
        Route::post('/ranker_assign/save', [RankerAssignController::class, 'save'])->name('ranker_assign.save');
        Route::get('/ranker_assign/edit', [RankerAssignController::class, 'edit'])->name('ranker_assign.edit');
        Route::post('/ranker_assign/update', [RankerAssignController::class, 'update'])->name('ranker_assign.update');
        Route::get('/ranker_assign/delete', [RankerAssignController::class, 'delete'])->name('ranker_assign.delete');
        Route::get('/ranker_assign/change', [RankerAssignController::class, 'change'])->name('ranker_assign.change');

        Route::get('/ranker_price', [RankerPriceController::class, 'list'])->name('ranker_price');
        Route::get('/ranker_price/add', [RankerPriceController::class, 'add'])->name('ranker_price.add');
        Route::post('/ranker_price/save', [RankerPriceController::class, 'save'])->name('ranker_price.save');
        Route::get('/ranker_price/edit', [RankerPriceController::class, 'edit'])->name('ranker_price.edit');
        Route::post('/ranker_price/update', [RankerPriceController::class, 'update'])->name('ranker_price.update');
        Route::get('/ranker_price/delete', [RankerPriceController::class, 'delete'])->name('ranker_price.delete');
        Route::get('/ranker_price/change', [RankerPriceController::class, 'change'])->name('ranker_price.change');

        Route::get('/country', [CountryController::class, 'list'])->name('country');
        Route::get('/country/add', [CountryController::class, 'add'])->name('country.add');
        Route::post('/country/save', [CountryController::class, 'save'])->name('country.save');
        Route::get('/country/edit', [CountryController::class, 'edit'])->name('country.edit');
        Route::post('/country/update', [CountryController::class, 'update'])->name('country.update');
        Route::get('/country/delete', [CountryController::class, 'delete'])->name('country.delete');
        Route::get('/country/change', [CountryController::class, 'change'])->name('country.change');

        Route::get('/state', [StateController::class, 'list'])->name('state');
        Route::get('/state/add', [StateController::class, 'add'])->name('state.add');
        Route::post('/state/save', [StateController::class, 'save'])->name('state.save');
        Route::get('/state/edit', [StateController::class, 'edit'])->name('state.edit');
        Route::post('/state/update', [StateController::class, 'update'])->name('state.update');
        Route::get('/state/delete', [StateController::class, 'delete'])->name('state.delete');
        Route::get('/state/change', [StateController::class, 'change'])->name('state.change');

        Route::get('/city', [CityController::class, 'list'])->name('city');
        Route::get('/city/add', [CityController::class, 'add'])->name('city.add');
        Route::post('/city/save', [CityController::class, 'save'])->name('city.save');
        Route::get('/city/edit', [CityController::class, 'edit'])->name('city.edit');
        Route::post('/city/update', [CityController::class, 'update'])->name('city.update');
        Route::get('/city/delete', [CityController::class, 'delete'])->name('city.delete');
        Route::get('/city/change', [CityController::class, 'change'])->name('city.change');

        Route::get('/district', [DistrictController::class, 'list'])->name('district');
        Route::get('/district/add', [DistrictController::class, 'add'])->name('district.add');
        Route::post('/district/save', [DistrictController::class, 'save'])->name('district.save');
        Route::get('/district/edit', [DistrictController::class, 'edit'])->name('district.edit');
        Route::post('/district/update', [DistrictController::class, 'update'])->name('district.update');
        Route::get('/district/delete', [DistrictController::class, 'delete'])->name('district.delete');
        Route::get('/district/change', [DistrictController::class, 'change'])->name('district.change');

        Route::get('/location', [LocationController::class, 'list'])->name('location');
        Route::get('/location/add', [LocationController::class, 'add'])->name('location.add');
        Route::post('/location/save', [LocationController::class, 'save'])->name('location.save');
        Route::get('/location/edit', [LocationController::class, 'edit'])->name('location.edit');
        Route::post('/location/update', [LocationController::class, 'update'])->name('location.update');
        Route::get('/location/delete', [LocationController::class, 'delete'])->name('location.delete');
        Route::get('/location/change', [LocationController::class, 'change'])->name('location.change');

        Route::get('/footer', [FooterController::class, 'list'])->name('footer');
        Route::get('/footer/add', [FooterController::class, 'add'])->name('footer.add');
        Route::post('/footer/save', [FooterController::class, 'save'])->name('footer.save');
        Route::get('/footer/edit', [FooterController::class, 'edit'])->name('footer.edit');
        Route::post('/footer/update', [FooterController::class, 'update'])->name('footer.update');
        Route::get('/footer/delete', [FooterController::class, 'delete'])->name('footer.delete');
        Route::get('/footer/change', [FooterController::class, 'change'])->name('footer.change');

        Route::get('/exam_location', [Exam_locationController::class, 'list'])->name('exam_location');
        Route::get('/exam_location/add', [Exam_locationController::class, 'add'])->name('exam_location.add');
        Route::post('/exam_location/save', [Exam_locationController::class, 'save'])->name('exam_location.save');
        Route::get('/exam_location/edit', [Exam_locationController::class, 'edit'])->name('exam_location.edit');
        Route::post('/exam_location/update', [Exam_locationController::class, 'update'])->name('exam_location.update');
        Route::get('/exam_location/delete', [Exam_locationController::class, 'delete'])->name('exam_location.delete');
        Route::get('/exam_location/change', [Exam_locationController::class, 'change'])->name('exam_location.change');

        Route::get('/common/state', [CommonController::class, 'state'])->name('common.state');
        Route::get('/common/city', [CommonController::class, 'city'])->name('common.city');
        Route::get('/common/source', [CommonController::class, 'source'])->name('common.source');
        Route::get('/common/chapter', [CommonController::class, 'chapter'])->name('common.chapter');
        Route::get('/common/topic', [CommonController::class, 'topic'])->name('common.topic');
        Route::get('/common/sub_topic', [CommonController::class, 'sub_topic'])->name('common.sub_topic');
        Route::get('/common/offline_exam', [CommonController::class, 'offline_exam'])->name('common.offline_exam');
        Route::get('/common/offline_exam_without_qus_paper', [CommonController::class, 'offline_exam_without_qus_paper'])->name('common.offline_exam_without_qus_paper');
        

        // Sourav Start
        Route::get('/banner', [BannerController::class, 'list'])->name('banner');
        Route::get('/banner/add', [BannerController::class, 'add'])->name('banner.add');
        Route::post('/banner/save', [BannerController::class, 'save'])->name('banner.save');
        Route::get('/banner/edit', [BannerController::class, 'edit'])->name('banner.edit');
        Route::post('/banner/update', [BannerController::class, 'update'])->name('banner.update');
        Route::get('/banner/delete', [BannerController::class, 'delete'])->name('banner.delete');
        Route::get('/banner/change', [BannerController::class, 'change'])->name('banner.change');

        Route::get('/scholarship', [ScholarshipController::class, 'list'])->name('scholarship');
        Route::get('/scholarship/edit', [ScholarshipController::class, 'edit'])->name('scholarship.edit');
        Route::post('/scholarship/update', [ScholarshipController::class, 'update'])->name('scholarship.update');
        Route::get('/scholarship/change', [ScholarshipController::class, 'change'])->name('scholarship.change');

        Route::get('/test_series', [TestSeriesController::class, 'list'])->name('test_series');
        Route::get('/test_series/add', [TestSeriesController::class, 'add'])->name('test_series.add');
        Route::post('/test_series/save', [TestSeriesController::class, 'save'])->name('test_series.save');
        Route::get('/test_series/edit', [TestSeriesController::class, 'edit'])->name('test_series.edit');
        Route::post('/test_series/update', [TestSeriesController::class, 'update'])->name('test_series.update');
        Route::get('/test_series/delete', [TestSeriesController::class, 'delete'])->name('test_series.delete');
        Route::get('/test_series/change', [TestSeriesController::class, 'change'])->name('test_series.change');

        Route::get('/interest', [InterestController::class, 'list'])->name('interest');
        Route::get('/interest/edit', [InterestController::class, 'edit'])->name('interest.edit');
        Route::post('/interest/update', [InterestController::class, 'update'])->name('interest.update');
        Route::get('/interest/change', [InterestController::class, 'change'])->name('interest.change');

        Route::get('/head_quater', [HeadQuaterController::class, 'list'])->name('head_quater');
        Route::get('/head_quater/edit', [HeadQuaterController::class, 'edit'])->name('head_quater.edit');
        Route::post('/head_quater/update', [HeadQuaterController::class, 'update'])->name('head_quater.update');
        Route::get('/head_quater/change', [HeadQuaterController::class, 'change'])->name('head_quater.change');

        Route::get('/real_story', [RealStoryController::class, 'list'])->name('real_story');
        Route::get('/real_story/edit', [RealStoryController::class, 'edit'])->name('real_story.edit');
        Route::post('/real_story/update', [RealStoryController::class, 'update'])->name('real_story.update');
        Route::get('/real_story/change', [RealStoryController::class, 'change'])->name('real_story.change');

        Route::get('/success_story', [SuccessStoryController::class, 'list'])->name('success_story');
        Route::get('/success_story/add', [SuccessStoryController::class, 'add'])->name('success_story.add');
        Route::post('/success_story/save', [SuccessStoryController::class, 'save'])->name('success_story.save');
        Route::get('/success_story/edit', [SuccessStoryController::class, 'edit'])->name('success_story.edit');
        Route::post('/success_story/update', [SuccessStoryController::class, 'update'])->name('success_story.update');
        Route::get('/success_story/delete', [SuccessStoryController::class, 'delete'])->name('success_story.delete');
        Route::get('/success_story/change', [SuccessStoryController::class, 'change'])->name('success_story.change');

        Route::get('/hurry_now', [HurryNowController::class, 'list'])->name('hurry_now');
        Route::get('/hurry_now/edit', [HurryNowController::class, 'edit'])->name('hurry_now.edit');
        Route::post('/hurry_now/update', [HurryNowController::class, 'update'])->name('hurry_now.update');
        Route::get('/hurry_now/change', [HurryNowController::class, 'change'])->name('hurry_now.change');

        Route::get('/learning', [LearningController::class, 'list'])->name('learning');
        Route::get('/learning/edit', [LearningController::class, 'edit'])->name('learning.edit');
        Route::post('/learning/update', [LearningController::class, 'update'])->name('learning.update');
        Route::get('/learning/change', [LearningController::class, 'change'])->name('learning.change');

        Route::get('/asked_question', [AskedQuestionController::class, 'list'])->name('asked_question');
        Route::get('/asked_question/add', [AskedQuestionController::class, 'add'])->name('asked_question.add');
        Route::post('/asked_question/save', [AskedQuestionController::class, 'save'])->name('asked_question.save');
        Route::get('/asked_question/edit', [AskedQuestionController::class, 'edit'])->name('asked_question.edit');
        Route::post('/asked_question/update', [AskedQuestionController::class, 'update'])->name('asked_question.update');
        Route::get('/asked_question/delete', [AskedQuestionController::class, 'delete'])->name('asked_question.delete');
        Route::get('/asked_question/change', [AskedQuestionController::class, 'change'])->name('asked_question.change');

        Route::get('/gallery', [GalleryController::class, 'list'])->name('gallery');
        Route::get('/gallery/add', [GalleryController::class, 'add'])->name('gallery.add');
        Route::post('/gallery/save', [GalleryController::class, 'save'])->name('gallery.save');
        Route::get('/gallery/delete', [GalleryController::class, 'delete'])->name('gallery.delete');
        Route::get('/gallery/change', [GalleryController::class, 'change'])->name('gallery.change');

        Route::get('/mention', [MentionController::class, 'list'])->name('mention');
        Route::get('/mention/add', [MentionController::class, 'add'])->name('mention.add');
        Route::post('/mention/save', [MentionController::class, 'save'])->name('mention.save');
        Route::get('/mention/delete', [MentionController::class, 'delete'])->name('mention.delete');
        Route::get('/mention/change', [MentionController::class, 'change'])->name('mention.change');

        // Sourav End

    });
    

    Route::get('counsellor', [CounsellorLoginController::class, 'index'])->name('counsellor.index');
    Route::post('counsellor/dologin', [CounsellorLoginController::class, 'dologin'])->name('counsellor.dologin');
    Route::post('counsellor/dologin', [CounsellorLoginController::class, 'dologin'])->name('counsellor.dologin');
    Route::get('counsellor/forgot-password', [CounsellorLoginController::class, 'forgot_password'])->name('counsellor.forgot_password');
    Route::post('counsellor/update-forgot-password', [CounsellorLoginController::class, 'updateforgotpassword'])->name('counsellor.updateforgotpassword');
    Route::get('counsellor/reset-password/{id}', [CounsellorLoginController::class, 'reset_password'])->name('counsellor.reset_password');
    Route::post('counsellor/update-reset-password', [CounsellorLoginController::class, 'updateresetpassword'])->name('counsellor.updateresetpassword');

    Route::name('counsellor.')
    ->prefix('counsellor')
    ->middleware(['counsellorAuth'])
    ->group(function () {
        Route::get('/dashboard', [CounsellorDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [CounsellorProfileController::class, 'index'])->name('profile');
        Route::post('/update-profile', [CounsellorProfileController::class, 'updateProfile'])->name('update_profile');
        Route::get('/profile/change-password', [CounsellorProfileController::class, 'changePassword'])->name('change_password');
        Route::post('/profile/update-password', [CounsellorProfileController::class, 'updatePassword'])->name('updatePassword');
        Route::get('/profile/setting', [CounsellorProfileController::class, 'setting'])->name('setting');
        Route::get('/profile/edit-setting', [CounsellorProfileController::class, 'editSetting'])->name('editSetting');
        Route::post('/profile/update-setting', [CounsellorProfileController::class, 'updateSetting'])->name('updateSetting');
        Route::get('/profile/logout', [CounsellorProfileController::class, 'logout'])->name('logout');
        
        Route::get('/student', [CounsellorUserController::class, 'list'])->name('student');
        Route::get('/student/add', [CounsellorUserController::class, 'add'])->name('student.add');
        Route::post('/student/save', [CounsellorUserController::class, 'save'])->name('student.save');
        Route::get('/student/edit', [CounsellorUserController::class, 'edit'])->name('student.edit');
        Route::post('/student/update', [CounsellorUserController::class, 'update'])->name('student.update');
        Route::get('/student/delete', [CounsellorUserController::class, 'delete'])->name('student.delete');
        Route::get('/student/change', [CounsellorUserController::class, 'change'])->name('student.change');

        Route::get('/student-upload', [CounsellorUserController::class, 'upload'])->name('student.upload');
        Route::post('/student-upload/save', [CounsellorUserController::class, 'upload_save'])->name('student.upload_save');
    });




