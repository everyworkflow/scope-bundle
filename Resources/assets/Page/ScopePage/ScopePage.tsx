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

const ScopePage = () => {
    const { dispatch: panelDispatch } = useContext(PanelContext);
    const { code = '' }: { code: string | undefined } = useParams();

    useEffect(() => {
        panelDispatch({
            type: ACTION_SET_PAGE_TITLE,
            payload: code !== '' && code !== 'default' ? 'Edit scope' : 'Scope',
        });
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
