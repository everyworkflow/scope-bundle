/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React, { useContext, useEffect, useState } from 'react';
import Form from 'antd/lib/form';
import Card from 'antd/lib/card';
import PanelContext from "@EveryWorkflow/PanelBundle/Context/PanelContext";
import DataFormInterface from "@EveryWorkflow/DataFormBundle/Model/DataFormInterface";
import AbstractFieldInterface from "@EveryWorkflow/DataFormBundle/Model/Field/AbstractFieldInterface";
import Remote from "@EveryWorkflow/PanelBundle/Service/Remote";
import PageHeaderComponent from "@EveryWorkflow/AdminPanelBundle/Component/PageHeaderComponent";
import BreadcrumbComponent from "@EveryWorkflow/AdminPanelBundle/Component/BreadcrumbComponent";
import DataFormComponent from "@EveryWorkflow/DataFormBundle/Component/DataFormComponent";
import { FORM_TYPE_HORIZONTAL } from "@EveryWorkflow/DataFormBundle/Component/DataFormComponent/DataFormComponent";
import AlertAction, { ALERT_TYPE_ERROR, ALERT_TYPE_SUCCESS } from "@EveryWorkflow/PanelBundle/Action/AlertAction";
import { ACTION_SET_PAGE_TITLE } from '@EveryWorkflow/PanelBundle/Reducer/PanelReducer';

interface ScopeFormProps {
    code: string;
}

const ScopeForm = ({ code }: ScopeFormProps) => {
    const { dispatch: panelDispatch } = useContext(PanelContext);
    const [form] = Form.useForm();
    const [dataForm, setDataForm] = useState<DataFormInterface>();
    const [loading, setLoading] = useState<boolean>(false);

    useEffect(() => {
        panelDispatch({
            type: ACTION_SET_PAGE_TITLE,
            payload: code !== '' ? 'Edit scope: ' + code : 'Scopes',
        });

        const handleResponse = (response: any) => {
            response.data_form.fields.forEach((item: AbstractFieldInterface) => {
                if (
                    item.name &&
                    response.item &&
                    Object.prototype.hasOwnProperty.call(response.item, item.name)
                ) {
                    item.value = response.item[item.name];
                }
            });
            console.log('response.data_form --:', response.data_form);
            if (dataForm) {
                form.resetFields();
            }
            setDataForm(response.data_form);
            setLoading(false);
        };

        const fetchItem = async () => {
            try {
                setLoading(true);
                const response: any = await Remote.get(
                    (code !== '' && code !== 'default') ? '/scope/' + code : '/scope/default'
                );
                handleResponse(response);
            } catch (error: any) {
                AlertAction({
                    message: error.message,
                    title: 'Fetch error',
                    type: ALERT_TYPE_ERROR,
                });
                setLoading(false);
            }
        };

        fetchItem();
    }, [panelDispatch, code]);

    const onSubmit = async (data: any) => {
        const submitData: any = {};
        Object.keys(data).forEach(name => {
            if (data[name]) {
                submitData[name] = data[name];
            }
        });

        const handlePostResponse = (response: any) => {
            if (response.message) {
                AlertAction({
                    message: response.message,
                    title: 'Form submit success',
                    type: ALERT_TYPE_SUCCESS,
                });
            }
        };

        try {
            const response = await Remote.post(
                code !== '' ? '/scope/' + code : '/scope/default',
                submitData
            );
            handlePostResponse(response);
        } catch (error: any) {
            AlertAction({
                message: error.message,
                title: 'Submit error',
                type: ALERT_TYPE_ERROR,
            });
        }
    };

    return (
        <>
            <PageHeaderComponent
                title={code !== '' && code !== 'default' ? `Code: ${code}` : 'Create new scope'}
                actions={[
                    {
                        label: 'Save changes',
                        onClick: () => {
                            form.submit();
                        },
                    }
                ]}
            />
            <BreadcrumbComponent />
            <Card
                className="app-container"
                title={'General'}
                style={{ marginBottom: 24 }}>
                {dataForm && !loading && (
                    <DataFormComponent
                        form={form}
                        formData={dataForm}
                        formType={FORM_TYPE_HORIZONTAL}
                        onSubmit={onSubmit}
                        labelCol={{ span: 8 }}
                        wrapperCol={{ span: 16 }}
                    />
                )}
            </Card>
        </>
    );
};

export default ScopeForm;
