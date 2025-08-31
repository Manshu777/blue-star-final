<?php 

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Customization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth')->only(['customize', 'storeCustomization']);
    // }

    public function index()
    {
        $mugs = Merchandise::where('name', 'like', '%mug%')
            ->orWhere('name', 'like', '%cup%')
            ->orWhere('description', 'like', '%mug%')
            ->orWhere('description', 'like', '%cup%')
            ->get();
        return view('shop.index', compact('mugs'));
    }

    public function customize($id)
    {
        $mug = Merchandise::findOrFail($id);
        return view('shop.customize', compact('mug'));
    }

    public function storeCustomization(Request $request, $id)
    {
        $request->validate([
            'custom_image' => 'required|image|mimes:png,jpeg|max:2048',
        ]);

        $mug = Merchandise::findOrFail($id);
        $customImagePath = $request->file('custom_image')->store('customizations', 'public');

        Customization::create([
            'user_id' => auth()->id(),
            'merchandise_id' => $mug->id,
            'custom_image_path' => $customImagePath,
        ]);

        return redirect()->route('shop.customize', $mug->id)
            ->with('success', 'Custom image uploaded successfully!');
    }
}