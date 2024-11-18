@section('customJs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Sortable(document.getElementById('menuList'), {
                handle: '.draggable-handle',
                animation: 150,
                onEnd: function(evt) {
                    let order = [];
                    document.querySelectorAll('#menuList > li').forEach((item, index) => {
                        order.push({
                            id: item.getAttribute('data-id'),
                            position: index + 1
                        });
                    });

                    fetch('{{ route('admin.menus.updateOrder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                order: order
                            })
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                            } else {
                                alert('Failed to update menu order.');
                            }
                        });
                }
            });
        });
    </script>
@endsection
