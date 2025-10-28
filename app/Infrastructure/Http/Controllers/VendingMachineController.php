<?php

namespace App\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Application\UseCases\GetVendingMachineStateUseCase;
use App\Application\UseCases\InsertCoinUseCase;
use App\Application\UseCases\ReturnCoinsUseCase;
use App\Application\UseCases\VendItemUseCase;
use App\Application\UseCases\ServiceRestockUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VendingMachineController extends Controller
{
    public function getVendingMachineState(GetVendingMachineStateUseCase $machine): JsonResponse
    {
        return response()->json($machine->execute());
    }

    public function insertCoin(Request $request, InsertCoinUseCase $useCase): JsonResponse
    {
        $value = $request->input('value');
        return response()->json(['balance' => $useCase->execute($value)]);
    }

    public function returnCoin(ReturnCoinsUseCase $useCase): JsonResponse
    {
        $returned_coins = $useCase->execute();
        return response()->json(['coins' => $returned_coins]);
    }

    public function vendItem(Request $request, VendItemUseCase $useCase): JsonResponse
    {
        $item = $request->input('item');
        try{
            $change = $useCase->execute($item);
            return response()->json(['change' => $change]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function serviceRestock(Request $request, ServiceRestockUseCase $useCase): JsonResponse
    {
        // Restocking 'item' or 'change'?
        $type = $request->input('type');
        if($type === 'item'){
            $itemName = $request->input('item_name');
            $count = $request->input('count');
            $useCase->addItem($itemName, $count);
        }elseif($type === 'change'){
            $value = $request->input('value');
            $count = $request->input('count');
            $useCase->addChange($value, $count);
        }
        return response()->json(['message' => 'Restocked']);
    }
}
