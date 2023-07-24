<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Team;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, string $teamId): JsonResponse
    {
        $this->authorize('create', [Payment::class, new Payment(), Team::query()->findOrFail($teamId)]);
        $team = Team::query()->findOrFail($teamId);
        $receiptUrl = $request->file('proveOfPayment')->store('receipt', ['disk' => 'public']);
        $paymentData = [
            'team_id' => $team->id,
            'transfer_receipt' => url('/') . Storage::url($receiptUrl),
        ];

        $payment = Payment::query()->updateOrCreate($paymentData);

        $responseData = [
            'status' => 1,
            'message' => 'success post proof of payment',
            'data' => [
                'team' => [
                    'teamId' => $teamId
                ],
                'payment' => $payment
            ]
        ];

        return response()->json($responseData);
    }
}
