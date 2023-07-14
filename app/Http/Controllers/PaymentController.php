<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Team;
use Exception;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, string $teamId): JsonResponse
    {
        try {
            $team = Team::query()->findOrFail($teamId);
            $receiptUrl = $request->file('proveOfPayment')->store('receipt', ['disk' => 'public']);
            $paymentData = [
                'team_id' => $team->id,
                'transfer_receipt' => $receiptUrl,
            ];

            Payment::query()->create($paymentData);

            $responseData = [
                'status' => 1,
                'message' => 'success post proof of payment',
                'data' => [
                    'team' => [
                        'teamId' => $teamId
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
