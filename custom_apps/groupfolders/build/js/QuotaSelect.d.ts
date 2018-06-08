/// <reference types="react" />
import { Component } from 'react';
import './EditSelect.css';
export interface QuotaSelectProps {
    options: {
        [name: string]: number;
    };
    value: number;
    size: number;
    onChange: (quota: number) => void;
}
export interface QuotaSelectState {
    options: {
        [name: string]: number;
    };
    isEditing: boolean;
    isValidInput: boolean;
}
export declare class QuotaSelect extends Component<QuotaSelectProps, QuotaSelectState> {
    state: QuotaSelectState;
    constructor(props: any);
    onSelect: (event: any) => void;
    onEditedValue: (value: any) => void;
    getUsedPercentage(): number;
    render(): JSX.Element;
}
