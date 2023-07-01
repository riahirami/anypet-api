<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnersRepository
{
    /**
     * @param Request $request
     * @return Partner
     */
    public function addPartner(Request $request)
    {
        $partner = new Partner();
        $partner->name = $request->input('name');
        $partner->description = $request->input('description');
        $partner->address = $request->input('address');
        $partner->link = $request->input('link');
        $partner->contact = $request->input('contact');

        $path = Storage::disk('partners')->put("", $request->file('logo'));
        $imageUrl = Storage::disk('partners')->url($path);
        $partner->logo = $imageUrl;

        $partner->save();

        foreach ($request->media as $file) {
            $media = new Media();
            $media->file_name = $file->getClientOriginalName();
            $media->file_path = url(Storage::url($file->store('public/partners')));

            $media->mime_type = $file->getClientMimeType();
            $partner->media()->save($media);
        }


        $partner->save();
        return $partner;
    }

    /**
     * @param Request $request
     * @param $partnerId
     * @return mixed
     */
    public function updatePartner(Request $request, $partnerId)
    {
        $partner = Partner::findOrFail($partnerId);
        $partner->name = $request->input('name');
        $partner->description = $request->input('description');
        $partner->address = $request->input('address');
        $partner->link = $request->input('link');
        $partner->contact = $request->input('contact');

        $path = Storage::disk('partners')->put("", $request->file('logo'));
        $imageUrl = Storage::disk('partners')->url($path);
        $partner->logo = $imageUrl;

        $partner->save();

        foreach ($request->media as $file) {
            $media = new Media();
            $media->file_name = $file->getClientOriginalName();
            $media->file_path = url(Storage::disk('partners')->url($file->store('public/partners')));
            $media->mime_type = $file->getClientMimeType();
            $partner->media()->save($media);
        }

        $partner->save();
        return $partner;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function Update(array $data, $id)
    {
        $partner = Partner::findOrFail($id);
        $partner->name = $data['name'];
        $partner->description = $data['description'];
        $partner->address = $data['address'];
        $partner->link = $data['link'];
        $partner->contact = $data['contact'];

        $path = Storage::disk('partners')->put("", $data['logo']);
        $imageUrl = Storage::disk('partners')->url($path);
        $partner->logo = $imageUrl;
        $partner->save();
        foreach ($data['media'] as $file) {
            $media = new Media();
            $media->file_name = $file->getClientOriginalName();
            $media->file_path = url(Storage::disk('partners')->url($file->store('public/partners')));
            $media->mime_type = $file->getClientMimeType();
            $partner->media()->save($media);
        }
        $partner->save();
        return $partner;

    }
    /**
     * @param $partnerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePartner($partnerId)
    {
        $partner = Partner::findOrFail($partnerId);
        $partner->delete();

        return response()->json(['message' => 'Partner deleted successfully']);
    }

    /**
     * @param $partnerId
     * @return mixed
     */
    public function getPartnerById($partnerId)
    {
        $partner = Partner::query()->with('media')->findOrFail($partnerId);

        return $partner;
    }

    /**
     * @param $perPage
     * @return mixed
     */
    public function getAllPartners()
    {
        $partners = Partner::query()->with('media')->paginate();

        return $partners;
    }



}
