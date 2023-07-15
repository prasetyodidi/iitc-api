<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus as PaymentStatusEnum;
use App\Http\Requests\UpdatePaymentStatusRequest;
use App\Models\PaymentStatus;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

class PaymentStatusController extends Controller
{

    public function update(UpdatePaymentStatusRequest $request, string $teamId): JsonResponse
    {
        $this->authorize('update', [PaymentStatus::class, new PaymentStatus()]);
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
    }
}
