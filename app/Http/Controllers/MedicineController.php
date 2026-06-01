<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $medicines = Medicine::where('created_by', Auth::id());

        if ($request->search) {
            $medicines = $medicines->where('medicine_name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $medicines = $medicines->where('category', $request->category);
        }

        if ($request->expiration_date) {
            $medicines = $medicines->whereDate('expiration_date', $request->expiration_date);
        }

        $medicines = $medicines->orderBy('expiration_date', 'asc')->paginate(10)->withQueryString();

        return view('medicines.index', compact('medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        $medicine = new Medicine();
        $medicine->medicine_name = $request->medicine_name;
        $medicine->description = $request->description;
        $medicine->category = $request->category;
        $medicine->quantity = $request->quantity;
        $medicine->expiration_date = $request->expiration_date;
        $medicine->created_by = Auth::id();
        $medicine->save();

        return redirect()->route('medicines.index')->with('toast_success', 'Medicine added successfully.');
    }

    public function update(Request $request, Medicine $medicine)
    {
        if ($medicine->created_by != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        $medicine->medicine_name = $request->medicine_name;
        $medicine->description = $request->description;
        $medicine->category = $request->category;
        $medicine->quantity = $request->quantity;
        $medicine->expiration_date = $request->expiration_date;
        $medicine->save();

        return redirect()->route('medicines.index')->with('toast_success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        if ($medicine->created_by != Auth::id()) {
            abort(403);
        }

        $medicine->delete();

        return redirect()->route('medicines.index')->with('toast_success', 'Medicine deleted successfully.');
    }
}
