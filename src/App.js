import { TabPanel } from "@wordpress/components";
import { Page, Container } from "@goodwp/goodenberg/admin/components";
import Basic from "./components/Basic";
// import Advanced from './components/Advanced';
import Actions from './components/Actions';

import styled from "@emotion/styled";

import { __ } from "@wordpress/i18n";

import { OptionsProvider, SourcesProvider } from './Context';

const TABS = [
    {
      name: "basic",
      title: __("Basic", "lifejacket-server"),
      value: "basic",
      Component: Basic,
    },
    // {
    //   name: "advanced",
    //   title: __("Advanced", "lifejacket-server"),
    //   value: "advanced",
    //   Component: Advanced,
    // },
  ];
  
  const StyledTabPanel = styled(TabPanel)`
    > .components-tab-panel__tabs {
      background: #fff;
      justify-content: center;
      border-bottom: 1px solid #e2e4e7;
    }
  `;
  

const App = () => {
    return (
        <OptionsProvider>
            <SourcesProvider>
                <Page name="lifejacket-settings">
                    <Page.Header 
                        title={__("LifeJacket Server", "lifejacket-server")} 
                        icon="admin-settings" 
                        actions={<Actions/>}>
                        {__("Settings", "lifejacket-server")}
                    </Page.Header>
                    <Container contained={"100%"} hasMargin={false}>
                        <StyledTabPanel
                            className="lifejacket-server-tabs"
                            tabs={TABS}
                            initialTabName={"basic"}
                            children={(Tab) => {
                            return <Tab.Component />;
                        }}
                        />
                    </Container>
                </Page>  
            </SourcesProvider>
        </OptionsProvider>
    );
  
  };
  export default App;