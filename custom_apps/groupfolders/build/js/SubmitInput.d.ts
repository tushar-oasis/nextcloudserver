/// <reference types="react" />
import * as React from 'react';
import { Component, InputHTMLAttributes } from 'react';
export interface SubmitInputProps extends InputHTMLAttributes<HTMLInputElement> {
    initialValue?: string;
    onSubmitValue: (value: string) => void;
}
export interface SubmitInputState {
    value: string;
}
export declare class SubmitInput extends Component<SubmitInputProps, SubmitInputState> {
    state: SubmitInputState;
    constructor(props: SubmitInputProps);
    onSubmit: (event: React.SyntheticEvent<any>) => void;
    render(): JSX.Element;
}
