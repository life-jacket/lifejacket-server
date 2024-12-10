import { useContext} from 'react';
import {
    __experimentalSurface as Surface,
    __experimentalVStack as VStack,
    ToggleControl

} from "@wordpress/components";

import { Container } from "@goodwp/goodenberg/admin/components";

import { __ } from "@wordpress/i18n";

import { valuesContext, sourcesContext } from '../Context';
import { Field } from './Field';

export default () => {
    const {data, updateData} = useContext(valuesContext);
    const {sources, updateSources} = useContext(sourcesContext);
  
    return (
        <Container contained={"800px"} as="div" hasMargin>
            <Surface style={{ padding: 20 }}>
                <VStack spacing={10}>
                    <Field source={sources.require_auth}>
                        <ToggleControl
                            label={__("Require Authentication", "lifejacket-server")}
                            help={__("Only supported by LifeJacket Server", "lifejacket-server")}
                            __nextHasNoMarginBottom
                            __next40pxDefaultSize
                            checked={ data.require_auth ?? false }
                            onChange={(value)=>{ updateData( {'require_auth': value }); }}
                            // 'require_auth'
                        />
                    </Field>
                    <Field source={sources.collect_stats}>
                        <ToggleControl
                            label={__("Collect Usage Stats", "lifejacket-server")}
                            // help={__("Only supported by LifeJacket Server", "lifejacket-server")}
                            __nextHasNoMarginBottom
                            __next40pxDefaultSize
                            checked={ data.collect_stats ?? false }
                            onChange={(value)=>{ updateData( {'collect_stats': value }); }}
                            // 'collect_stats'
                        />
                    </Field>
                </VStack>
            </Surface>
        </Container>
    );
};