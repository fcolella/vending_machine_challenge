<?php

namespace App\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Application\UseCases\InsertCoinUseCase;
use App\Application\UseCases\ReturnCoinUseCase;
use App\Application\UseCases\VendItemUseCase;
use App\Application\UseCases\ServiceRestockUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VendingMachineController extends Controller
{
    public function insertCoin(Request $request, InsertCoinUseCase $useCase): JsonResponse
    {
        $value = $request->input('value');
        $useCase->execute($value);
        return response()->json(['message' => 'Coin inserted']);
    }

    public function returnCoin(ReturnCoinUseCase $useCase): JsonResponse
    {
        $returned = $useCase->execute();
        return response()->json(['returned' => $returned]);
    }

    public function vendItem(Request $request, VendItemUseCase $useCase): JsonResponse
    {
        $item = $request->input('item');
        try{
            $result = $useCase->execute($item);
            return response()->json(['result' => $result]);
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
