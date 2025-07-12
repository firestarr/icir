<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\InspectionDimension;
use App\Models\InspectionVisualCheck;
use App\Models\InspectionSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class InspectionController extends Controller
{
    public function index()
    {
        $inspections = Inspection::with(['signatures'])->latest()->get();
        return response()->json($inspections);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'material' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'po_no' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $inspection = Inspection::create($request->only([
                'material', 'po_no', 'sample_size', 'supplier', 'issue_date',
                'instrument_used', 'description', 'location', 'nomer_instrument',
                'ic_no', 'lot_size_qty', 'qty_good', 'unit', 'data_outgoing',
                'qty_no_good', 'coa_data', 'icp_data', 'aql', 'acc_reject',
                'supplier_lot_no'
            ]));

            // Create default dimension entries
            $dimensionTypes = ['thickness', 'width', 'length', 'diameter', 'other'];
            foreach ($dimensionTypes as $type) {
                InspectionDimension::create([
                    'inspection_id' => $inspection->id,
                    'dimension_type' => $type,
                ]);
            }

            // Create default visual check entries
            $visualCheckTypes = [
                'DIRTY (Oil, Dust)', 'WRONG COLOR', 'WRINGKLE/BUBBLE', 
                'SCRATCHS/FLASH', 'TEAR/TORN', 'BROKEN / DAMAGE',
                'CHECK ICP', 'CHECK COA', 'CHECK SAFETY', 'OTHER'
            ];
            foreach ($visualCheckTypes as $type) {
                InspectionVisualCheck::create([
                    'inspection_id' => $inspection->id,
                    'check_type' => $type,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Inspection created successfully',
                'data' => $inspection->load(['dimensions', 'visualChecks'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to create inspection'], 500);
        }
    }

    public function show($id)
    {
        $inspection = Inspection::with(['dimensions', 'visualChecks', 'signatures'])->findOrFail($id);
        return response()->json($inspection);
    }

    public function update(Request $request, $id)
    {
        $inspection = Inspection::findOrFail($id);

        try {
            DB::beginTransaction();

            $inspection->update($request->only([
                'material', 'po_no', 'sample_size', 'supplier', 'issue_date',
                'instrument_used', 'description', 'location', 'nomer_instrument',
                'ic_no', 'lot_size_qty', 'qty_good', 'unit', 'data_outgoing',
                'qty_no_good', 'coa_data', 'icp_data', 'aql', 'acc_reject',
                'supplier_lot_no'
            ]));

            // Update dimensions
            if ($request->has('dimensions')) {
                foreach ($request->dimensions as $dimensionData) {
                    if (isset($dimensionData['id'])) {
                        $dimension = InspectionDimension::find($dimensionData['id']);
                        if ($dimension) {
                            $dimension->update($dimensionData);
                        }
                    }
                }
            }

            // Update visual checks
            if ($request->has('visual_checks')) {
                foreach ($request->visual_checks as $checkData) {
                    if (isset($checkData['id'])) {
                        $check = InspectionVisualCheck::find($checkData['id']);
                        if ($check) {
                            $check->update($checkData);
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Inspection updated successfully',
                'data' => $inspection->load(['dimensions', 'visualChecks', 'signatures'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to update inspection'], 500);
        }
    }

    public function addSignature(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:inspector,checker,approver',
            'signature_data' => 'required|string',
            'user_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $inspection = Inspection::findOrFail($id);

        // Remove existing signature for this role
        InspectionSignature::where('inspection_id', $id)
            ->where('role', $request->role)
            ->delete();

        $signature = InspectionSignature::create([
            'inspection_id' => $id,
            'role' => $request->role,
            'signature_data' => $request->signature_data,
            'user_name' => $request->user_name,
            'signed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Signature added successfully',
            'data' => $signature
        ]);
    }

    public function generatePdf($id)
    {
        $inspection = Inspection::with(['dimensions', 'visualChecks', 'signatures'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.inspection', compact('inspection'));
        
        return $pdf->download('ICIR-' . $inspection->ic_no . '.pdf');
    }

    public function destroy($id)
    {
        $inspection = Inspection::findOrFail($id);
        $inspection->delete();

        return response()->json(['message' => 'Inspection deleted successfully']);
    }
}