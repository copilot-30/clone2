<div class="overflow-y-auto h-full w-full ">
    <div class="  mx-auto p-5 border w-[90%] max-w-4xl shadow-lg -md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-emerald-700">Author: Dr. {{ ucwords($soapNote->doctor->first_name .' ' .$soapNote->doctor->last_name)}}</h3>
            
        </div> 
        <form id="soapNoteForm" method="POST" action="{{ route('doctor.soap-notes.update', $soapNote->id) }}">
            @csrf 
    
    
            <input type="hidden" name="patient_id" value="{{ $soapNote->patient_id }}">
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="follow_up_date">Date</label>
                <input type="date" value="{{ $soapNote->date->format('Y-m-d') ?? date('Y-m-d') }}" name="follow_up_date" id="follow_up_date" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Subjective</label>
                    <textarea name="subjective" id="subjective" placeholder="Subjective" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->subjective}}</textarea>
                    <textarea name="chief_complaint" id="chief_complaint" placeholder="Chief complaint (CC)" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows=2>{{$soapNote->chief_complaint}}</textarea>
                    <textarea name="history_of_illness" id="history_of_illness" placeholder="History of present illness (HPI)" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->history_of_illness}}</textarea>
 
                </div>

                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Objective</label>
                    <textarea name="objective" id="objective" placeholder="Enter objective findings." class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3">{{$soapNote->objective}}</textarea>
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-emerald-600 border-emerald-600" data-tab-target="vital-signs-tab-content">Vital Signs</button>
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab-target="lab-results-tab-content">Lab Results & Files</button>
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab-target="remarks-tab-content">Remarks</button>
                        </nav>
                    </div>

                    <div id="objective-tab-content">
                        <div id="vital-signs-tab-content" class="tab-pane space-y-2">
                            <div class="grid grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Weight</label>
                                    <input type="text" name="weight" id="weight" placeholder="kg" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Height</label>
                                    <input type="text" name="height" id="height" placeholder="cm" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">BMI</label>
                                    <input type="text" name="bmi" id="bmi" placeholder="kg/m²" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Blood Pressure </label>
                                    <input type="text" name="blood_pressure" id="blood_pressure" placeholder="mmHg" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Oxygen Saturation (%)</label>
                                    <input type="text" name="oxygen_saturation" id="oxygen_saturation" placeholder="%" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
    
                            <div class="grid grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Respiratory Rate</label>
                                    <input type="text" name="respiratory_rate" id="respiratory_rate" placeholder="breaths/min" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Heart Rate (bpm)</label>
                                    <input type="text" name="heart_rate" id="heart_rate" placeholder="bpm" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Body Temperature</label>
                                    <input type="text" name="body_temperature" id="body_temperature" placeholder="°C" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-xs font-bold mb-2">Capillary Blood Glucose</label>
                                    <input type="text" name="capillary_blood_glucose" id="capillary_blood_glucose" placeholder="mg/dL" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                            <div class="">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Addtional notes about vitals.</label>
                                <textarea name="vitals_remark" placeholder="Addtional notes about vitals." class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            </div>
                      
                        </div>
                        <div id="lab-results-tab-content" class="tab-pane hidden space-y-2">
                           
                            <div class="mt-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Upload Files</label>
                                <div id="drop-area" class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center cursor-pointer hover:border-gray-400">
                                    <p class="text-gray-500">Drag & drop files here, or click to select</p>
                                    <input type="file" id="file-upload" name="lab_files[]" multiple class="hidden">
                                </div>
                                <div id="file-list" class="mt-2"></div>
                            </div>
                             <textarea name="file_remarks" id="file_remarks" placeholder="File description" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="7"></textarea>
                            
                        </div>
                        <div id="remarks-tab-content" class="tab-pane hidden space-y-2">
                            <textarea name="remarks" id="remarks" placeholder="Remarks" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Assessment</label>
                    <textarea name="assessment" id="assessment" placeholder="Enter Assessment" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3">{{$soapNote->assessment}}</textarea>
                    <textarea name="diagnosis" id="diagnosis" placeholder="Enter Diagnosis" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->diagnosis}}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Plan</label>
                    <textarea name="plan" id="plan" placeholder="Enter treatment plan" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3">{{$soapNote->plan}}</textarea>
                    <textarea name="prescription" id="prescription" placeholder="Enter Prescription details" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->prescription}}</textarea>
                    <textarea name="test_request" id="test_request" placeholder="Test Request" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->test_request}}</textarea>
                    <button class="px-4 py-2 bg-emerald-600 text-white font-semibold -md hover:bg-emerald-700"><i class="fas fa-calendar-alt"></i> Schedule Follow up</button>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4"> 
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-semibold -md hover:bg-emerald-700">Update Note</button>
            </div>
        </form> 
    </div>
</div>