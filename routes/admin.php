<?php
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Route;
	
    use App\Http\Middleware\AdminAuth;

    use App\Http\Controllers\Admin\AuthController;
    use App\Http\Controllers\Admin\AdminController;
    use App\Http\Controllers\Admin\ProfileController;
    use App\Http\Controllers\Admin\DashboardController;
    use App\Http\Controllers\Admin\CountryController;
    use App\Http\Controllers\Admin\StateController;
    use App\Http\Controllers\Admin\DistrictController;
    use App\Http\Controllers\Admin\CityController;
    use App\Http\Controllers\Admin\LocationController;
    use App\Http\Controllers\Admin\RankerController;
    use App\Http\Controllers\Admin\RankerAppointmentController;
    use App\Http\Controllers\Admin\RankerAssignController;
    use App\Http\Controllers\Admin\RankerPriceController;
    use App\Http\Controllers\Admin\StudentController;
    use App\Http\Controllers\Admin\Offline_examController;
    use App\Http\Controllers\Admin\Offline_exam_resultController;
    use App\Http\Controllers\Admin\Online_examController;
    use App\Http\Controllers\Admin\Exam_locationController;
    use App\Http\Controllers\Admin\CommonController;

    use App\Http\Controllers\Admin\SubjectController;
    use App\Http\Controllers\Admin\ChapterController;
    use App\Http\Controllers\Admin\SourceController;
    use App\Http\Controllers\Admin\Question_sourceController;
    use App\Http\Controllers\Admin\QuestionController;


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


    Route::get('/', [AuthController::class, 'index'])->name('admin.index');
    Route::post('dologin', [AuthController::class, 'dologin'])->name('admin.dologin');

    Route::group(['middleware' => 'adminAuth'], function () {
    	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        

        Route::get('/profile', [AdminController::class, 'index'])->name('profile');
        Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('update_profile');
        Route::get('/profile/change-password', [AdminController::class, 'changePassword'])->name('change_password');
        Route::post('/profile/update-password', [AdminController::class, 'updatePassword'])->name('updatePassword');
        Route::get('/profile/setting', [AdminController::class, 'setting'])->name('setting');
        Route::get('/profile/edit-setting', [AdminController::class, 'editSetting'])->name('editSetting');
        Route::post('/profile/update-setting', [AdminController::class, 'updateSetting'])->name('updateSetting');
        Route::get('/profile/logout', [AuthController::class, 'logout'])->name('logout');
        
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
        Route::get('/question-upload/save', [QuestionController::class, 'upload_save'])->name('question.upload_save');
        
        Route::get('/chapter', [ChapterController::class, 'list'])->name('chapter');
        Route::get('/chapter/add', [ChapterController::class, 'add'])->name('chapter.add');
        Route::post('/chapter/save', [ChapterController::class, 'save'])->name('chapter.save');
        Route::get('/chapter/edit', [ChapterController::class, 'edit'])->name('chapter.edit');
        Route::post('/chapter/update', [ChapterController::class, 'update'])->name('chapter.update');
        Route::get('/chapter/delete', [ChapterController::class, 'delete'])->name('chapter.delete');
        Route::get('/chapter/change', [ChapterController::class, 'change'])->name('chapter.change');
        
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
        
        Route::get('/offline_exam', [Offline_examController::class, 'list'])->name('offline_exam');
        Route::get('/offline_exam/add', [Offline_examController::class, 'add'])->name('offline_exam.add');
        Route::post('/offline_exam/save', [Offline_examController::class, 'save'])->name('offline_exam.save');
        Route::get('/offline_exam/edit', [Offline_examController::class, 'edit'])->name('offline_exam.edit');
        Route::post('/offline_exam/update', [Offline_examController::class, 'update'])->name('offline_exam.update');
        Route::get('/offline_exam/delete', [Offline_examController::class, 'delete'])->name('offline_exam.delete');
        Route::get('/offline_exam/change', [Offline_examController::class, 'change'])->name('offline_exam.change');

        Route::get('/offline_exam_result/upload', [Offline_exam_resultController::class, 'add'])->name('offline_exam_result.upload');
        Route::post('/offline_exam_result/upload-save', [Offline_exam_resultController::class, 'save'])->name('offline_exam_result.upload_save');
        
        Route::get('/online_exam', [Online_examController::class, 'list'])->name('online_exam');
        Route::get('/online_exam/add', [Online_examController::class, 'add'])->name('online_exam.add');
        Route::post('/online_exam/save', [Online_examController::class, 'save'])->name('online_exam.save');
        Route::get('/online_exam/edit', [Online_examController::class, 'edit'])->name('online_exam.edit');
        Route::post('/online_exam/update', [Online_examController::class, 'update'])->name('online_exam.update');
        Route::get('/online_exam/delete', [Online_examController::class, 'delete'])->name('online_exam.delete');
        Route::get('/online_exam/change', [Online_examController::class, 'change'])->name('online_exam.change');
        
        Route::get('/ranker', [RankerController::class, 'list'])->name('ranker');
        Route::get('/ranker/add', [RankerController::class, 'add'])->name('ranker.add');
        Route::post('/ranker/save', [RankerController::class, 'save'])->name('ranker.save');
        Route::get('/ranker/edit', [RankerController::class, 'edit'])->name('ranker.edit');
        Route::post('/ranker/update', [RankerController::class, 'update'])->name('ranker.update');
        Route::get('/ranker/delete', [RankerController::class, 'delete'])->name('ranker.delete');
        Route::get('/ranker/change', [RankerController::class, 'change'])->name('ranker.change');
        
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
?>