import * as React from 'react';
import './FolderGroups.css';
import {SyntheticEvent} from "react";

function hasPermissions(value: number, check: number): boolean {
	return (value & check) === check;
}

export interface FolderGroupsProps {
	groups: { [group: string]: number },
	allGroups?: string[],
	onAddGroup: (name: string) => void;
	removeGroup: (name: string) => void;
	edit: boolean;
	showEdit: (event: SyntheticEvent<any>) => void;
	onSetPermissions: (name: string, permissions: number) => void;
}

export function FolderGroups({groups, allGroups = [], onAddGroup, removeGroup, edit, showEdit, onSetPermissions}: FolderGroupsProps) {
	if (edit) {
		const setPermissions = (change: number, groupId: string): void => {
			const newPermissions = groups[groupId] ^ change;
			onSetPermissions(groupId, newPermissions);
		};

		const rows = Object.keys(groups).map(groupId => {
			const permissions = groups[groupId];
			return <tr key={groupId}>
				<td>
					{groupId}
				</td>
				<td className="permissions">
					<input type="checkbox"
						   onChange={setPermissions.bind(null, OC.PERMISSION_UPDATE | OC.PERMISSION_CREATE | OC.PERMISSION_DELETE, groupId)}
						   checked={hasPermissions(permissions, (OC.PERMISSION_UPDATE | OC.PERMISSION_CREATE | OC.PERMISSION_DELETE))}/>
				</td>
				<td className="permissions">
					<input type="checkbox"
						   onChange={setPermissions.bind(null, OC.PERMISSION_SHARE, groupId)}
						   checked={hasPermissions(permissions, OC.PERMISSION_SHARE)}/>
				</td>
				<td>
					<a onClick={removeGroup.bind(this, groupId)}>
						<img src={OC.imagePath('core', 'actions/close')}/>
					</a>
				</td>
			</tr>
		});

		return <table className="group-edit"
					  onClick={event => event.stopPropagation()}>
			<thead>
			<tr>
				<th>Group</th>
				<th>Write</th>
				<th>Share</th>
				<th/>
			</tr>
			</thead>
			<tbody>
			{rows}
			<tr>
				<td colSpan={4}>
					<GroupSelect allGroups={allGroups.filter(i => !groups[i])}
								 onChange={onAddGroup}/>
				</td>
			</tr>
			</tbody>
		</table>
	} else {
		if (Object.keys(groups).length === 0) {
			return <span>
				<em>none</em>
				<a className="icon icon-rename" onClick={showEdit}/>
			</span>
		}
		return <a className="action-rename" onClick={showEdit}>
			{Object.keys(groups).join(', ')}
		</a>
	}
}

interface GroupSelectProps {
	allGroups: string[];
	onChange: (name: string) => void;
}

function GroupSelect({allGroups, onChange}: GroupSelectProps) {
	if (allGroups.length === 0) {
		return <div/>;
	}
	const options = allGroups.map(group => {
		return <option key={group} value={group}>{group}</option>;
	});

	return <select
		onChange={event => {
			onChange && onChange(event.target.value)
		}}
	>
		<option>{t('groupfolders', 'Add group')}</option>
		{options}
	</select>
}
