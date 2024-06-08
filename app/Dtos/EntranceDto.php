<?php


namespace App\Dtos;

use App\Enums\TransTypeEnum;
use App\Http\Requests\StoreEntranceRequest;
use App\Http\Requests\UpdateEntranceRequest;
use App\Http\Resources\EntranceCommentResource;
use App\Models\Entrance;
use App\Models\Product;
use App\Models\ProductTrans;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Global\errorHttp;
use function App\Global\successHttp;

class EntranceDto
{
    // Guardar lo sdatos
    public function store(StoreEntranceRequest $request):JsonResponse
    {
        try {

            return  DB::transaction(function () use ($request) {
                // Guardar los datos
                $entrance = Entrance::create($request->validated());

                // Crear el comentario
                $entrance->comment()->create([
                    'info' => $request->info
                ]);



                // Recorrer el array para crear la transacion
                foreach ($request->order as $key => $value) {

                    // Incrementar el stock de los productos
                    Product::where('id', $value['id'])
                        ->increment('stock', $value['quantity']);

                    // Ingreasar los datos de la entrada
                    ProductTrans::create([
                        'product_id' => $value['id'],
                        'quantity' => $value['quantity'],
                        'type' => TransTypeEnum::ENTRADA
                    ]);
                }

                // Respuesta
                $response = successHttp(config('msj.created'),200, $entrance);

                // Devolver los datos
                return $response;

            });


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config('msj.error'),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }


    // Obtener todos los registro de esto
    public function show(Request $request)
    {
        try {

            // Obtener los datos
            $from =  Carbon::parse($request->get('from'));
            $to = Carbon::parse($request->get('to'));
            $limit = $request->get('limit',10);


            // Buscar los datos en la base de datos
            $data = Entrance::where('status', false)
                ->whereBetween('created_at',[$from, $to])
                ->latest()
                ->simplePaginate($limit);


            // Formatear los da tos
            $dataR = EntranceCommentResource::collection($data)->response()->getData(true);


            // Respuesta
            $response = successHttp(config('msj.get'),200, $dataR);

            // Devolver la respuesta
            return $response;


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config('msj.error'),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

    // Editar la orden seleccionada
    public function edit(Entrance $entrance)
    {
        try {

            // Tomar los datos
            $data = Entrance::where('status', false)
                ->where('id', $entrance->id)
                ->first();

            // Formatear los datos
            $dataR = new EntranceCommentResource($data);


            // Respuesta
            $response = successHttp(config('msj.get'),200, $dataR);

            // Devolver los datos
            return $response;

        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config('msj.error'),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }

    // Actualizar los datos
    public function update(UpdateEntranceRequest $request, Entrance $entrance)
    {
        try {

            return  DB::transaction(function () use ($request, $entrance) {
                // Guardar los datos
                $entrance = Entrance::where('status', false)
                    ->where('id', $entrance->id)
                    ->first();

                // Actualizar los datos
                $entrance->order = $request->order;
                $entrance->save();

                // Actualzar los datos
                $entrance->comment()->update([
                    'info' => $request->info
                ]);


                // Recorrer el array para crear la transacion
                foreach ($request->order as $key => $value) {




                    // ? Si la validacion es para sumar
                    if($value['act'] === '+')
                    {
                         // Incrementar el stock de los productos
                        Product::where('id', $value['id'])
                        ->increment('stock', $value['quantity']);

                        // Ingreasar los datos de la entrada
                        ProductTrans::create([
                            'product_id' => $value['id'],
                            'quantity' => $value['quantity'],
                            'type' => TransTypeEnum::ACTUALIZACION
                        ]);


                    }else{


                        // Reducir los productos del inventario
                        Product::where('id', $value['id'])
                        ->decrement('stock', $value['quantity']);

                        // Ingreasar los datos de la entrada
                        ProductTrans::create([
                            'product_id' => $value['id'],
                            'quantity' => $value['quantity'],
                            'type' => TransTypeEnum::ACTUALIZACION
                        ]);
                    }


                }

                // Respuesta
                $response = successHttp(config('msj.updated'),200, $entrance);

                // Devolver los datos
                return $response;

            });


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config('msj.error'),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }
    }


    // Elimianar las entrada
    public function destroy(Entrance $entrance)
    {
        try {
            //code...
            // Actualizar los datos a true
            $entrance->status = true;
            // Guardar los datos
            $entrance->save();

            // Deshabilitar el comentario
            $entrance->comment()->update([
                'status' => true
            ]);

            // Respuesta
            $response = successHttp(config('msj.deleted'),200, $entrance);

            return $response;
        } catch (\Throwable $th) {
            //throw $th;

            // Respuesta
            $response = errorHttp(config('msj.error'),400, $th->getMessage());

            // Devolver la respuesta
            return $response;
        }

    }

}
