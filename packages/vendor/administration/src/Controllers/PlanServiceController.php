<?php

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PlanServiceController extends Controller
{
    public function create(Request $request)
    {
        $plans = Plan::orderBy('name')->get(['id', 'name']);
        $selectedPlanId = $request->integer('plan_id');

        return view('administration::plan-services.create', compact('plans', 'selectedPlanId'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'service_type' => 'required|in:free,paid',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'is_active' => 'nullable|boolean',
            'main_media_type' => 'required|in:image,video_upload,video_url',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'main_video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200',
            'main_video_url' => 'nullable|url|max:2000',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'gallery_videos.*' => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $serviceType = $request->input('service_type', 'free');
        $price = $serviceType === 'paid' ? (float) $request->input('price', 0) : 0;

        $mainImagePath = null;
        $mainVideoPath = null;
        $mainVideoUrl = null;

        if ($request->main_media_type === 'image' && $request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('plan-services/main', 'public');
        }

        if ($request->main_media_type === 'video_upload' && $request->hasFile('main_video')) {
            $mainVideoPath = $request->file('main_video')->store('plan-services/main', 'public');
        }

        if ($request->main_media_type === 'video_url') {
            $mainVideoUrl = $request->input('main_video_url');
        }

        $gallery = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $img) {
                $gallery[] = [
                    'type' => 'image',
                    'path' => $img->store('plan-services/gallery/images', 'public'),
                ];
            }
        }
        if ($request->hasFile('gallery_videos')) {
            foreach ($request->file('gallery_videos') as $vid) {
                $gallery[] = [
                    'type' => 'video',
                    'path' => $vid->store('plan-services/gallery/videos', 'public'),
                ];
            }
        }

        $service = PlanService::create([
            'plan_id' => $request->integer('plan_id'),
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'description' => $request->input('description'),
            'content' => $request->input('content'),
            'service_type' => $serviceType,
            'price' => $price,
            'currency' => strtoupper($request->input('currency', 'CAD')),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => 0,
            'main_media_type' => $request->input('main_media_type'),
            'main_image_path' => $mainImagePath,
            'main_video_path' => $mainVideoPath,
            'main_video_url' => $mainVideoUrl,
            'gallery' => $gallery,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service créé avec succès.',
            'service_id' => $service->id,
            'redirect' => route('plans.edit', $service->plan_id),
        ]);
    }
}

