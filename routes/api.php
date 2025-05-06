    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\PatientController;


    Route::post('/patients', [PatientController::class, 'store']);