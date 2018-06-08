/// <reference types="react" />
import './FolderGroups.css';
import { SyntheticEvent } from "react";
export interface FolderGroupsProps {
    groups: {
        [group: string]: number;
    };
    allGroups?: string[];
    onAddGroup: (name: string) => void;
    removeGroup: (name: string) => void;
    edit: boolean;
    showEdit: (event: SyntheticEvent<any>) => void;
    onSetPermissions: (name: string, permissions: number) => void;
}
export declare function FolderGroups({groups, allGroups, onAddGroup, removeGroup, edit, showEdit, onSetPermissions}: FolderGroupsProps): JSX.Element;
