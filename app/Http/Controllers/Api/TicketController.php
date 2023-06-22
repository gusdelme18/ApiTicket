<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\TicketResource;


class TicketController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tickets = Ticket::all();    
        $response = [
            'success' => true,
            'data'    => TicketResource::collection($tickets),
            'message' => 'Tickets retrieved successfully.',
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'title' => 'required',
            'users_id' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);
   
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
            ];
    
            if(!empty($validator->errors())){
                $response['data'] = $validator->errors();
            }
    
            return response()->json($response, 404);       
        }

        $ticket = Ticket::create($input);

        $response = [
            'success' => true,
            'data'    => new TicketResource($ticket),
            'message' => 'Ticket created successfully.',
        ];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket,$id)
    {
        //
        $ticket = Ticket::find($id);
  
        if (is_null($ticket)) {
            $response = [
                'success' => false,
                'message' => 'Ticket not found.',
            ];
    
            return response()->json($response, 404);  
        }

        $response = [
            'success' => true,
            'data'    => new TicketResource($ticket),
            'message' => 'Ticket retrieved successfully.',
        ];

        return response()->json($response, 200);    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket, $id)
    {
        //
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'title' => 'required',
            'users_id' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);
   
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
            ];
    
            if(!empty($validator->errors())){
                $response['data'] = $validator->errors();
            }
    
            return response()->json($response, 404);   
        }

        $ticket = Ticket::where('id', $id)->update(["title" => $input['title'],
                                                        "description" => $input['description'],
                                                        "status" => $input['status'],
                                                        "users_id" => $input['users_id']
                                                    ]);

        $response = [
            'success' => true,
            'data'    => $ticket,
            'message' => 'Ticket updated successfully.',
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket, $id)
    {
        //
        $tickets = Ticket::where('id', $id)->delete();   
        $response = [
            'success' => true,
            'data'    => '',
            'message' => 'Ticket deleted successfully.'
        ];

        return response()->json($response, 200);
    }
}
