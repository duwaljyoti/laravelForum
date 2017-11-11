<div class="panel panel-default">
    <div v-if="editing" v-cloak>
        <div class="panel-heading">
            <div class="level">
                <span class="flex">
                    <div class="form-group">
                        <input class="form-control" v-model="form.title">
                    </div>
                </span>
            </div>
        </div>
        <div class="panel-body">
            <div class="form-area">
                <textarea class="form-control" name="" id="" cols="20" rows="10" v-model="form.body"></textarea>
            </div>
        </div>
        <div class="panel-footer level">
            <button class="btn-xs btn-primary mr-1" @click="update">Update</button>
            <button class="btn-xs mr-1" @click="resetForm">Cancel</button>
        </div>
    </div>

    <div v-else>
        <div class="panel-heading">
            <div class="level">
                <span class="flex">
                    <img src="{{ $thread->creator->avatar_path }}"
                         height="50" width="50" class="mr-1">

                    <a href="{{ route('profile', $thread->creator) }}">
                        {{ $thread->creator->name }}
                    </a> posted <span v-text="title"></span>
                </span>
                @can('update', $thread)
                    <form action={{ $thread->path() }} method='POST'>
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type='submit' class='btn btn-link'>Delete</button>
                    </form>
                @endcan
            </div>

        </div>
        <div class="panel-body" v-text="body">
        </div>
        <div class="panel-footer level" v-show="canUpdate">
            <div class="panel-footer level" v-show="canUpdate">
                <button class="btn-xs" @click="editing=true">Edit</button>
            </div>
        </div>
    </div>
</div>