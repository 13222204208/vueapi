<?php

namespace App\Models;

use App\Util\Arr;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

/**
 * App\Models\Permission
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users

 */
class Permission extends \Spatie\Permission\Models\Permission
{

    /**
     * 获取列表
     *
     * @param array $data
     * @return array
     */
    public static function getList(array $data): array
    {
        $where[] = ['guard_name', '=', $data['guard_name']];

        $model = Permission::where($where)
            ->when($data['name'] ?? null, function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['title'] ?? null, function ($query) use ($data) {
                return $query->where('title', 'like', '%' . $data['title'] . '%');
            })
            ->when($data['path'] ?? null, function ($query) use ($data) {
                return $query->where('path', 'like', '%' . $data['path'] . '%');
            })
            ->when($data['start_at'] ?? null, function ($query) use ($data) {
                return $query->whereBetween('created_at', [$data['start_at'], $data['end_at']]);
            });


        $total = $model->count('id');

        $permissions = $model->select(['id', 'pid', 'name', 'title', 'icon', 'path', 'component',
            'guard_name', 'sort', 'hidden', 'created_at', 'updated_at'])
            ->orderBy($data['sort'] ?? 'sort', $data['order'] === 'ascending' ? 'asc' : 'desc')
            ->offset(($data['offset'] - 1) * $data['limit'])
            ->limit($data['limit'])
            ->get();


        return [
            'permissions' => $permissions,
            'total' => $total
        ];
    }

    /**
     * 删除权限
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public static function __deleted(int $id): array
    {
        $result = false;
        $message = __('message.common.delete.fail');

        if (Permission::where('pid', '=', $id)->count('id') > 0) {
            $message =
                __('message.common.delete.fail_message', ['message' => __('message.permission.delete_pid')]);
        } else {
            $permission = Permission::find($id);
            $result = $permission->delete();
            if ($result) {
                $message = __('message.common.delete.success');
            }
        }

        return [
            'result' => $result,
            'message' => $message
        ];
    }

    /**
     * 树形结构
     *
     * @param array $data
     * @return array
     */
    public static function tree(array $data): array
    {
        $permissions = Permission::where('guard_name', '=', $data['guard_name'])
            ->select(['id', 'pid', 'name', 'title', 'icon'])
            ->orderByDesc('sort')
            ->get();

        return Arr::getTree($permissions->toArray());
    }

    /**
     * 修改目录排序与层级
     *
     * @param int $dragging 被操作者的ID
     * @param int $drop 操作的ID
     * @param string $type 操作类型
     * @return bool
     */
    public static function drop(int $dragging, int $drop, string $type) : bool
    {
        $update = [];
        switch ($type) {
            case 'before':
                $dropPermission = Permission::where('id', $drop)->get(['sort', 'pid'])->first();
                $update = ['sort' => $dropPermission->sort + 1, 'pid' => $dropPermission->pid];
                break;
            case 'inner':
                $update = ['pid' => $drop];
                break;
            case 'after':
                $dropPermission = Permission::where('id', $drop)->get(['sort', 'pid'])->first();
                $sort = $dropPermission->sort;
                $update = ['sort' => ($sort - 1) < 1 ? 1 : $sort - 1, 'pid' => $dropPermission->pid];
                break;
            default:
        }
        if (count($update) > 0) {
            return Permission::where('id', $dragging)->update($update);
        }
        return false;
    }
}
