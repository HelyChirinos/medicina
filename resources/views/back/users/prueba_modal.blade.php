@extends('layouts.back')

@section('title')
    &vert; User
@endsection

@section('content')

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_modal">
        Launch demo modal
    </button>
  
    <!-- Modal -->
    @include('back.users.modal_create')

@endsection

@push('scripts')

  <script type="module">

    $('#add_modal').submit(function(e) 
    {
        e.preventDefault();
        let datos = $('#form_add').serialize();
        $.ajax({
            url: "{{ route('back.users.store') }}",
            method: 'post',
            data:  datos,
            success: function(result)
            {
                    $("#help_card").css("display", "block");
                    $("#error_card").css("display", "none");
                    $('#lista_error').html('');                  
                    $('#add_modal').modal('hide');
                    alert('Se grabo el Usuario');
                    resetAddForm();
            },
            error: function(result) 
            {
              if (result.status == 422) {
                  $("#help_card").css("display", "none");
                  $('#form_add').find(".print-error-msg").find("ul").html('');
                  $('#form_add').find(".print-error-msg").css('display','block');
                  $.each( result.responseJSON.errors, function( key, value ) {
                        $('#form_add').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                  });
              } else {
                 alert('Tipo de Error:' + result.status+' '+result.responseText);

              }
            }        
        });
    });

    function resetAddForm() 
    {
        $('#form_add')[0].reset();
    }

    $('#add_modal').on('hide.bs.modal', function (e) {
            resetAddForm();
    })

  </script>
@endpush