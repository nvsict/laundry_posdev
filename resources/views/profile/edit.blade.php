@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <h2 class="mb-4">Profile</h2>

        <div class="row">
            <div class="col-lg-8 mx-auto">

                {{-- Update Profile Information --}}
                <div class="card mb-4">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password --}}
                <div class="card mb-4">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Delete Account --}}
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        Delete Account
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection