<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\ListTableController;
use App\Http\Controllers\ListItemsTableController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredUserOwnerController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GroupingController;
use App\Http\Controllers\GroupingSkillController;
use App\Http\Controllers\JuridicalPersonController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\PhysicalPersonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserGroupingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SupplierController;
use App\Http\Middleware\OrganizationMiddleware;
use App\Http\Middleware\SubscribedMiddleware;
use App\Http\Middleware\UserAccountMiddleware;
use App\Http\Middleware\UserNetworkMiddleware;
use App\Http\Middleware\UserVerified;
use App\Models\Network;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenericTableController;
use App\Http\Controllers\TableFieldsController;
use \App\Http\Controllers\GenericTableValueController;
use App\Http\Controllers\TicketController;
use \Illuminate\Support\Str;
use \Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Auth\RedirectTokenController;
use App\Http\Controllers\TicketMovementController;
use App\Http\Middleware\HasTicketMiddleware;
use App\Http\Controllers\TicketGroupController;
use App\Http\Controllers\TicketGroupUserController;
use App\Http\Controllers\TicketRuleGroupController;
use App\Http\Controllers\TicketConditionsController;
use App\Http\Controllers\TicketActionsController;
use App\Http\Controllers\TicketOperatorsController;
use App\Http\Controllers\TicketRuleConditionsController;
use App\Http\Controllers\TicketRuleActionsController;
use App\Http\Controllers\FileUploadController;

Route::get('auth', function () {
    if (Auth::guard('api')->check()) {
        return response()->json(['message' => 'Authenticated.']);
    } else {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
});

Route::middleware('guest')->group(function () {
    Route::get('products', [StripeController::class, 'getProducts']);
    Route::post('owner', [RegisteredUserOwnerController::class, 'store']);
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::post('forgot-password-link', [PasswordResetLinkController::class, 'store']);
    Route::post('reset-password', [NewPasswordController::class, 'store']);
    Route::post('verification-email-notification', [EmailVerificationNotificationController::class, 'store']);
    Route::post('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['throttle:6,1']);
    Route::post('network/{network}/account/{account}/accept-invite-user', [RegisteredUserController::class, 'accept']);
});

Route::middleware('auth:api')->post('/generate-redirect-token', [RedirectTokenController::class, 'generate']);
Route::post('/exchange-temp-token', [RedirectTokenController::class, 'exchange']);

Route::middleware(['auth:api', UserVerified::class])->group(function () {

    Route::post('teste', function (Request $request) {

        $request->validate([
            'name' => 'string|required',
            'content' => ['required', 'array', new \App\Rules\ValidateContent()],
        ]);

        return "ahaha";
    });

    Route::put('password', [PasswordController::class, 'update']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('roles', [RoleController::class, 'index']);

    Route::prefix('network')->group(function () {
        Route::get('/', [NetworkController::class, 'index']);

        Route::prefix('{network}')->scopeBindings()->group(function () {
            Route::middleware(UserNetworkMiddleware::class)->group(function () {
                Route::get('/', [NetworkController::class, 'show']);
                Route::put('/', [NetworkController::class, 'update']);

                Route::controller(CostCenterController::class)->group(function () {
                    Route::get('/cost-center', 'index');
                    Route::get('/cost-center/{cost_center}', 'show');
                    Route::post('/cost-center', 'store');
                    Route::put('/cost-center/{cost_center}', 'update');
                    Route::delete('/cost-center/{cost_center}', 'destroy');
                });

                Route::prefix('account')->group(function () {
                    Route::post('/', [AccountController::class, 'store']);
                    Route::get('/', [AccountController::class, 'index']);

                    Route::prefix('{account}')->group(function () {
                        Route::middleware(UserAccountMiddleware::class)->group(function () {
                            Route::put('/', [AccountController::class, 'update']);
                            Route::delete('/', [AccountController::class, 'destroy']);
                            Route::get('/', [AccountController::class, 'show']);

                            Route::post('/checkout', [StripeController::class, 'getCheckout']);

                            Route::get('/role', [RoleController::class, 'indexByAccount']);

                            // crud listas

                            Route::controller(ListTableController::class)->group(function () {
                                Route::get('/list', 'index');
                                Route::get('/list/{list}', 'getListById');
                                Route::post('/list', 'store');
                                Route::put('/list/{list}', 'update');
                                Route::delete('/list/{list}', 'destroy');
                            });

                            Route::prefix('list/{list}')->group(function () {
                                Route::controller(ListItemsTableController::class)->group(function () {
                                    Route::get('/item', 'index');
                                    Route::post('/item', 'store');
                                    Route::get('/item/{item}', 'show');
                                    Route::put('/item/{item}', 'update');
                                    Route::delete('/item/{item}', 'destroy');
                                });
                            });

                            // tabelas genericas

                            Route::controller(GenericTableController::class)->group(function () {
                               Route::get('/table', 'index');
                               Route::get('/table/{table}', 'getTableById');
                               Route::post('/table', 'store');
                               Route::put('/table/{table}', 'update');
                               Route::delete('/table/{table}', 'destroy');
                            });

                            Route::prefix('table/{table}')->group(function () {
                                Route::controller(TableFieldsController::class)->group(function () {
                                    Route::get('/field', 'index');
                                    Route::get('/field/{field}', 'show');
                                    Route::post('/field', 'store');
                                    Route::put('/field/{field}', 'update');
                                    Route::delete('/field/{field}', 'destroy');
                                });
                            });

                            Route::prefix('table/{table}/field/{field}')->group(function () {
                                Route::controller(GenericTableValueController::class)->group(function () {
                                    Route::get('/value', 'index');
                                    Route::post('/value', 'store');
                                    Route::post('/values', 'storeMany');
                                    Route::put('/values', 'updateMany');
                                    Route::delete('/value/{value}', 'destroy');
                                    Route::post('/values/delete', 'deleteMany');
                                });
                            });

                            Route::controller(CustomerController::class)->group(function () {
                                Route::get('/customer', 'index');
                                Route::post('/customer', 'store');
                                Route::get('/customer/{customer}', 'show');
                                Route::put('/customer/{customer}', 'update');
                                Route::delete('/customer/{customer}', 'destroy');
                            });

                            Route::controller(SupplierController::class)->group(function () {
                                Route::get('/supplier', 'index');
                                Route::post('/supplier', 'store');
                                Route::get('/supplier/{supplier}', 'show');
                                Route::put('/supplier/{supplier}', 'update');
                                Route::delete('/supplier/{supplier}', 'destroy');
                            });

                            Route::controller(GroupingController::class)->group(function () {
                                Route::get('/grouping', 'index');
                                Route::post('/grouping', 'store');
                                Route::get('/grouping/{grouping}', 'show');
                                Route::put('/grouping/{grouping}', 'update');
                                Route::delete('/grouping/{grouping}', 'destroy');
                            });

                            Route::controller(GroupingSkillController::class)->group(function () {
                                Route::post('/grouping/{grouping}/skill', 'store');
                                Route::get('/grouping/{grouping}/skill', 'show');
                                Route::delete('/grouping/{grouping}/skill', 'destroy');
                            });

                            Route::controller(SkillController::class)->group(function () {
                                Route::get('/skill', 'index');
                                Route::post('/skill', 'store');
                                Route::get('/skill/{skill}', 'show');
                                Route::put('/skill/{skill}', 'update');
                                Route::delete('/skill/{skill}', 'destroy');
                            });

                            Route::controller(PhysicalPersonController::class)->group(function () {
                                Route::get('/physicalPerson', 'index');
                                Route::post('/physicalPerson', 'store');
                                Route::get('/physicalPerson/{physicalPerson}', 'show');
                                Route::put('/physicalPerson/{physicalPerson}', 'update');
                                Route::delete('/physicalPerson/{physicalPerson}', 'destroy');
                            });

                            Route::controller(JuridicalPersonController::class)->group(function () {
                                Route::get('/juridicalPerson', 'index');
                                Route::post('/juridicalPerson', 'store');
                                Route::get('/juridicalPerson/{juridicalPerson}', 'show');
                                Route::put('/juridicalPerson/{juridicalPerson}', 'update');
                                Route::delete('/juridicalPerson/{juridicalPerson}', 'destroy');
                            });

                            Route::get('/subscriptions', [StripeController::class, 'getUserSubscriptions']);

//                                Route::post('/accept-invite-user', [RegisteredUserController::class, 'accept']);
                            // * MIDDLEWARE VERIFICA SE ASSINATURA É VÁLIDA
                            Route::middleware(SubscribedMiddleware::class)->group(function () {
                                //teste sem subscription mover esse pra fora
                                Route::post('/invite-user', [RegisteredUserController::class, 'store']);

                                Route::middleware(HasTicketMiddleware::class)->group(function () {
                                    // MÓDULO DE TICKETS COM PERMISSAO APENAS SE EXISTIR ASSINATURA
                                    Route::controller(TicketController::class)->group(function () {
                                        Route::get('/ticket', 'index');
                                        Route::get('/ticket/{ticket}', 'getTicketById');
                                        Route::post('/ticket', 'store');
                                        Route::patch('/ticket/{ticket}', 'update');
                                        Route::delete('/ticket/{ticket}', 'destroy');
                                        Route::get('/tickets-by-hour', 'ticketByHour');
                                        Route::get('/ticket-stats', 'getTicketStats');
                                        Route::get('/assigned', 'getAssignedTicketsByUser');
                                        Route::get('/assigned-agent', 'getTicketsAssignedToAllAgents');
                                        Route::get('/agents', 'ticketAgents');
                                        Route::get('/sla-expired', 'getExpiredTickets');
                                        Route::get('/soon-expired', 'getSoonToExpireTickets');
                                        Route::get('/ticket-customer', 'getTicketCountByCustomer');
                                    });

                                    Route::prefix('ticket/{ticket}')->group(function () {
                                        Route::controller(TicketMovementController::class)->group(function () {
                                            Route::get('/ticket-move', 'index');
                                            Route::post('/ticket-move', 'create');
                                        });

                                        Route::controller(FileUploadController::class)->group(function () {
                                            Route::post('/attachments', 'store');
                                        });
                                    });

                                    Route::controller(TicketGroupcontroller::class)->group(function () {
                                        Route::get('/ticket-group', 'index');
                                        Route::post('/ticket-group', 'store');
                                        Route::get('/ticket-group/{ticketGroup}', 'show');
                                        Route::put('/ticket-group/{ticketGroup}', 'update');
                                        Route::delete('/ticket-group/{ticketGroup}', 'destroy');
                                    });

                                    Route::controller(TicketGroupUserController::class)->group(function () {
                                        Route::get('/ticket-group-user', 'index');
                                        Route::post('/ticket-group-user', 'store');
                                        Route::get('/ticket-group-user/{ticketGroupUser}', 'show');
                                        Route::put('/ticket-group-user/{ticketGroupUser}', 'update');
                                        Route::delete('/ticket-group-user/{ticketGroupUser}', 'destroy');
                                    });

                                    Route::controller(TicketRuleGroupController::class)->group(function () {
                                       Route::get('/ticket-rule-group', 'index');
                                       Route::post('/ticket-rule-group', 'store');
                                    });

                                    Route::controller(TicketRuleConditionsController::class)->group(function () {
                                       Route::get('/ticket-rule-condition', 'index');
                                       Route::post('/ticket-rule-condition', 'store');
                                    });

                                    Route::controller(TicketConditionsController::class)->group(function () {
                                       Route::get('/ticket-condition', 'index');
                                       Route::post('/ticket-condition', 'store');
                                    });

                                    Route::controller(TicketActionsController::class)->group(function () {
                                       Route::get('/ticket-action', 'index');
                                       Route::post('/ticket-action', 'store');
                                    });

                                    Route::controller(TicketOperatorsController::class)->group(function () {
                                       Route::get('/ticket-operator', 'index');
                                       Route::post('/ticket-operator', 'store');
                                    });

                                    Route::controller(TicketRuleActionsController::class)->group(function () {
                                       Route::get('/ticket-rule-action', 'index');
                                       Route::post('/ticket-rule-action', 'store');
                                    });

                                });


                                Route::controller(ProjectController::class)->group(function () {
                                    Route::get('/project', 'index');
                                    Route::post('/project', 'store');
                                    Route::get('/project/{project}', 'show');
                                    Route::put('/project/{project}', 'update');
                                    Route::delete('/project/{project}', 'destroy');
                                });

                                Route::controller(ProjectUserGroupingController::class)->group(function () {
                                    Route::post('/project/{project}/user_grouping', 'store');
                                    Route::get('/project/{project}/user_grouping', 'show');
                                    Route::delete('/project/{project}/user_grouping', 'destroy');
                                });
                                // * MIDDLEWARE MODULO ORGANIZATION
                                Route::middleware(OrganizationMiddleware::class)->prefix('organization')->group(function () {
                                });
                            });
                        });
                    });
                });
            });

        });

    });
});
