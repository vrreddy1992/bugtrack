<div class="table-responsive">
    <table class="table table-bordered" ng-show="permissions">
        <thead>
            <tr>
                <th>
                    Module
                </th>
                <th>
                    View
                </th>
                <th>
                    Edit
                </th>
                <th>
                    Delete
                </th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="module in modules">
                <td class="td-alt"> @{{ module.name }} </td>
                <td class="td-alt" ng-init="module.role_modules.view_access = (module.role_modules.view_access) ? module.role_modules.view_access : 0">
                    <div class="cr-block">
                        <input type="radio" id="view-none-@{{ module.id }}" class="custom-radio" ng-model="module.role_modules.view_access" ng-value="0" ng-click="saveRoleModules(module)">
                        <label for="view-none-@{{module.id }}">None</label>
                    </div>
                    <div class="cr-block">
                        <input type="radio" id="view-all-@{{ module.id }}" class="custom-radio" ng-model="module.role_modules.view_access" ng-value="1" ng-click="saveRoleModules(module)">
                        <label for="view-all-@{{ module.id }}">All Records</label>
                    </div>
                    <div class="cr-block">
                        <input type="radio" id="view-own-@{{ module.id }}" class="custom-radio" ng-model="module.role_modules.view_access" ng-value="2" ng-click="saveRoleModules(module)">
                        <label for="view-own-@{{ module.id }}">Own Records</label>
                    </div>
                </td>
                <td class="td-alt" ng-init="module.role_modules.edit_access = (module.role_modules.edit_access) ? module.role_modules.edit_access : 0">
                    <div class="cr-block">
                    	<input type="radio" id="edit-none-@{{ module.id }}" class="custom-radio" ng-model="module.role_modules.edit_access" ng-value="0" ng-click="saveRoleModules(module)">
                        <label for="edit-none-@{{ module.id }}">None</label>
                    </div>
                    <div class="cr-block">
                        <input type="radio" id="edit-all-@{{ module.id }}" class="custom-radio" ng-model="module.role_modules.edit_access" ng-value="1" ng-click="saveRoleModules(module)" ng-disabled="(module.role_modules.view_access == 0) ? true : false">
                        <label for="edit-all-@{{ module.id }}">All Records</label>
                    </div>
                    <div class="cr-block">
                        <input type="radio" id="edit-own-@{{ module.id }}" class="custom-radio" ng-model="module.role_modules.edit_access" ng-value="2" ng-click="saveRoleModules(module)" ng-disabled="(module.role_modules.view_access == 0) ? true : false">
                        <label for="edit-own-@{{ module.id }}">Own Records</label>
                    </div>
                </td>
                <td class="td-alt" ng-init="module.role_modules.delete_access = (module.role_modules.delete_access) ? module.role_modules.delete_access : 0">
                    <div class="cr-block">
                	   <input type="radio" id="delete-none-@{{ module.id }}" class="custom-radio" ng-model="module.role_modules.delete_access" ng-value="0" ng-click="saveRoleModules(module)">
                        <label for="delete-none-@{{ module.id }}">None</label>
                    </div>
                    <div class="cr-block">
                        <input type="radio" id="delete-all-@{{ module.id }}" class="custom-radio" ng-model="module.role_modules.delete_access" ng-value="1" ng-click="saveRoleModules(module)" ng-disabled="(module.role_modules.view_access == 0) ? true : false">
                        <label for="delete-all-@{{ module.id }}">All Records</label>
                    </div>
                    <div class="cr-block">
                        <input type="radio" id="delete-own-@{{ module.id }}" class="custom-radio" ng-model="module.role_modules.delete_access" ng-value="2" ng-click="saveRoleModules(module)" ng-disabled="(module.role_modules.view_access == 0) ? true : false">
                        <label for="delete-own-@{{ module.id }}">Own Records</label>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
