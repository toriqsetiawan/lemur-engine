@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Bot Allowed Sites
        </h1>
   </section>
   <div class="content">
       @include('layouts.feedback')
       <div class="box box-primary">
           <div class="box-body edit-page">
               <div class="row">
                   <div class="col-md-12">
                       {!! Form::model($botAllowedSite, ['route' => ['botAllowedSites.update', $botAllowedSite->slug], 'method' => 'patch', 'data-test'=>$htmlTag.'-edit-form', 'class'=>'validate', 'name'=>$htmlTag.'-edit']) !!}

                            @include('bot_allowed_sites.fields')

                            <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('botAllowedSites.index') }}" class="btn btn-default">Cancel</a>
                            </div>


                       {!! Form::close() !!}
                    </div>
               </div>
           </div>
       </div>
   </div>
<!-- Slug Field Edit Modal -->
@include('layouts.edit_slug_modal')
@endsection
@push('scripts')
    {{ Html::script('js/validation.js') }}
    {{ Html::script('js/select2.js') }}
@endpush
