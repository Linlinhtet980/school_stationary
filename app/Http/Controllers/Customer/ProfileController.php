<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display customer profile
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        $addresses = Address::where('user_id', Auth::id())
                          ->orderBy('is_default', 'desc')
                          ->get();
        
        $orders = Order::where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->get();
                      
        $wishlistItems = Wishlist::with('item')
                                ->where('user_id', Auth::id())
                                ->latest()
                                ->take(5)
                                ->get();
        
        return view('customer.profile', compact('customer', 'addresses', 'orders', 'wishlistItems'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
        ]);

        $customer = Auth::user()->customer;
        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update profile image
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $customer = Auth::user()->customer;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('customers', 'public');
            
            // Delete old image if exists
            if ($customer->image && Storage::disk('public')->exists($customer->image)) {
                Storage::disk('public')->delete($customer->image);
            }
            
            $customer->update(['image' => $path]);
        }

        return back()->with('success', 'Profile image updated successfully!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Add new address
     */
    public function addAddress(Request $request)
    {
        $request->validate([
            'label' => 'nullable|string|max:100',
            'address_line' => 'required|string',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'is_default' => 'nullable|boolean',
        ]);

        // If setting as default, remove default from other addresses
        if ($request->has('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => Auth::id(),
            'label' => $request->label,
            'address_line' => $request->address_line,
            'city' => $request->city,
            'phone' => $request->phone,
            'is_default' => $request->has('is_default'),
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    /**
     * Update address
     */
    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'label' => 'nullable|string|max:100',
            'address_line' => 'required|string',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'is_default' => 'nullable|boolean',
        ]);

        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        // If setting as default, remove default from other addresses
        if ($request->has('is_default')) {
            Address::where('user_id', Auth::id())->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update([
            'label' => $request->label,
            'address_line' => $request->address_line,
            'city' => $request->city,
            'phone' => $request->phone,
            'is_default' => $request->has('is_default'),
        ]);

        return back()->with('success', 'Address updated successfully!');
    }

    /**
     * Delete address
     */
    public function deleteAddress($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        if ($address->is_default) {
            return back()->with('error', 'Cannot delete default address. Please set another address as default first.');
        }

        $address->delete();
        return back()->with('success', 'Address deleted successfully!');
    }

    /**
     * Set default address
     */
    public function setDefaultAddress($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        // Remove default from all addresses
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        
        // Set selected address as default
        $address->update(['is_default' => true]);
        
        return back()->with('success', 'Default address updated successfully!');
    }

    /**
     * Display wishlist
     */
    public function wishlist()
    {
        $wishlistItems = Wishlist::with(['item' => function($query) {
                                $query->where('status', 'active')->with('variants');
                            }])
                            ->where('user_id', Auth::id())
                            ->latest()
                            ->paginate(12);
        
        return view('customer.wishlist', compact('wishlistItems'));
    }

    /**
     * Add to wishlist
     */
    public function addToWishlist(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
                        ->where('item_id', $request->item_id)
                        ->exists();

        if ($exists) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item already in wishlist.'
                ], 400);
            }
            return back()->with('error', 'Item already in wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to wishlist successfully!'
            ]);
        }

        return back()->with('success', 'Item added to wishlist!');
    }

    /**
     * Remove from wishlist
     */
    public function removeFromWishlist($id)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlistItem->delete();
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from wishlist successfully!'
            ]);
        }
        
        return back()->with('success', 'Item removed from wishlist!');
    }

    /**
     * Store a product review
     */
    public function storeReview(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $exists = \App\Models\Review::where('user_id', Auth::id())
                                   ->where('item_id', $request->item_id)
                                   ->exists();

        if ($exists) {
            return back()->with('error', 'You have already reviewed this item.');
        }

        \App\Models\Review::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'hidden',
        ]);

        return back()->with('success', 'Thank you for your review! It will be visible once approved by an admin.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }

    /**
     * Mark a specific notification as read and redirect
     */
    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Check type and redirect accordingly
        if ($notification->type == 'order_status' && isset($notification->data['order_id'])) {
            return redirect()->route('customer.orders.show', $notification->data['order_id']);
        }

        if (in_array($notification->type, ['wishlist_back_in_stock', 'wishlist_low_stock']) && isset($notification->data['item_id'])) {
            return redirect()->route('shop.show', $notification->data['item_id']);
        }

        return back();
    }
}