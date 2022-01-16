/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React, { useContext, useEffect } from 'react';
import { useParams } from "react-router-dom";
import PanelContext from "@EveryWorkflow/PanelBundle/Context/PanelContext";
import { ACTION_SET_PAGE_TITLE } from "@EveryWorkflow/PanelBundle/Reducer/PanelReducer";
import Row from "antd/lib/row";
import Col from "antd/lib/col";
import ScopeSidebar from './ScopeSidebar';
import ScopeForm from './ScopeForm';
import AdminPanelContext from '@EveryWorkflow/AdminPanelBundle/Context/AdminPanelContext';
import { ACTION_HIDE_FOOTER, ACTION_SHOW_FOOTER } from '@EveryWorkflow/AdminPanelBundle/Reducer/AdminPanelReducer';

const ScopePage = () => {
    const { dispatch: panelDispatch } = useContext(PanelContext);
    const { dispatch: adminPanelDispatch } = useContext(AdminPanelContext);
    const { code = 'default' }: any = useParams();

    useEffect(() => {
        panelDispatch({
            type: ACTION_SET_PAGE_TITLE,
            payload: code !== 'default' ? 'Edit scope' : 'Scope',
        });
        adminPanelDispatch({ type: ACTION_HIDE_FOOTER });
        return () => {
            adminPanelDispatch({ type: ACTION_SHOW_FOOTER });
        };
    }, [code]);

    return (
        <div className="list-page-with-tree-sidebar">
            <Row gutter={0}>
                <Col style={{ width: 420 }}>
                    <ScopeSidebar />
                </Col>
                <Col flex="auto" style={{ width: 'calc(100% - 420px)' }}>
                    <ScopeForm code={code} />
                </Col>
            </Row>
        </div>
    );
}

export default ScopePage;
