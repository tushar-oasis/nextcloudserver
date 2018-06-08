/// <reference types="jquery" />
import Thenable = JQuery.Thenable;
export interface Folder {
    mount_point: string;
    quota: number;
    size: number;
    groups: {
        [group: string]: number;
    };
}
export declare class Api {
    getUrl(endpoint: string): string;
    listFolders(): Thenable<Folder[]>;
    listGroups(): Thenable<string[]>;
    createFolder(mountPoint: string): Thenable<number>;
    deleteFolder(id: number): Thenable<void>;
    addGroup(folderId: number, group: string): Thenable<void>;
    removeGroup(folderId: number, group: string): Thenable<void>;
    setPermissions(folderId: number, group: string, permissions: number): Thenable<void>;
    setQuota(folderId: number, quota: number): Thenable<void>;
    renameFolder(folderId: number, mountpoint: string): Thenable<void>;
}
