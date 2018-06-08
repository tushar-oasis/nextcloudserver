/// <reference types="react" />
import { Component } from 'react';
import { Api, Folder } from './Api';
import './App.css';
export interface AppState {
    folders: Folder[];
    groups: string[];
    newMountPoint: string;
    editingGroup: number;
    editingMountPoint: number;
    renameMountPoint: string;
}
export declare class App extends Component<{}, AppState> {
    api: Api;
    state: AppState;
    componentDidMount(): void;
    createRow: () => void;
    deleteFolder(id: number): void;
    addGroup(folderId: number, group: string): void;
    removeGroup(folderId: number, group: string): void;
    setPermissions(folderId: number, group: string, newPermissions: number): void;
    setQuota(folderId: number, quota: number): void;
    renameFolder(folderId: number, newName: string): void;
    render(): JSX.Element;
}
