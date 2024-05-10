

@if(session('success'))
    <div class="modal" id="modalSuccess" tabindex="-1" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-success"><i class="fas fa-check-double"></i> Correcto! </h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body  text-success">
                    {{ session('success') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        @if(session('success'))
            // Muestra el modal
            $(document).ready(function() {
                $('#modalSuccess').modal('show');
            });
        @endif
</script>
@endif

@if($errors->any())
    <div id="errorModal" class="modal" tabindex="-1" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-danger"><i class="mdi mdi-alert-outline"></i> Error! </h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-ustyled mb-0 text-danger">
                        @foreach ( $errors->all() as $error )
                            <li><h3>{{ $error }}</h3></li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        @if($errors->any())
            // Muestra el modal por su ID
            $(document).ready(function() {
                $('#errorModal').modal('show');
            });
        @endif
    </script>
@endif