import { useContext, useState } from "react";

import { Button, Spinner } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { valuesContext } from '../Context';


export default () => {
    const [ processing, updateProcessing ] = useState( false );
    const {data, updateData, storeData} = useContext(valuesContext);

    return (
        <>
            {processing && <Spinner/>}
            <Button 
                variant="primary"
                disabled={processing}
                onClick={()=>{
                    updateProcessing(true);
                    storeData()
                    .then(()=>{ 
                        updateProcessing(false); 
                    });
                }}
            >
                {__("Save",'lifejacket-server')}
            </Button>
        </>
    );
}