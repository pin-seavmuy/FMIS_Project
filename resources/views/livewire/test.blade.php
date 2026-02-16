<div>
    <h2>{{ hello() }}</h2>
    <table>
        @forelse ($datas as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['email'] }}</td>
            </tr>
        @empty
            <tr>
                <td>Empty</td>
            </tr>          
        @endforelse
    </table>
    {{-- Because you are alive, everything is possible. - Thich Nhat Hanh --}}
    
    <input wire:model.live="user.name" placeholder="User name"/>
    <input wire:model.live="user.email" placeholder="User email"/>
    <input wire:model.live="user.password" placeholder="User password"/>
    <button wire:click="createUser">Create User</button>
</div>
