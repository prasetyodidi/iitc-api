<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus as PaymentStatusEnum;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\PaymentStatus;
use App\Models\Team;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{

    public function update(UpdatePaymentRequest $request, string $teamId): JsonResponse
    {
        try {
            $team = Team::query()->findOrFail($teamId);

            $paymentStatusData = [
                'team_id' => $team->id,
                'status' => $request->input('isApprove') ? PaymentStatusEnum::Valid : PaymentStatusEnum::Invalid,
                'reason' => $request->input('reason'),
            ];

            $paymentStatus = PaymentStatus::query()->create($paymentStatusData);

            $responseData = [
                'status' => 1,
                'message' => 'success update payment status',
                'data' => [
                    'payment' => [
                        'team_id' => $teamId,
                        'status' => $paymentStatus->status,
                        'reason' => $paymentStatus->reason,
                    ]
                ]
            ];

            return response()->json($responseData);
        } catch (Exception $exception) {
            $responseData = [
                'status' => 0,
                'message' => $exception->getMessage(),
            ];

            return response()->json($responseData, 400);
        }
    }
}
