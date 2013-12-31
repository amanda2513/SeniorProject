RSpec::Matchers.define :contain_a_mediation_request_for do |media|
  
  match do |log_of_requests_to_media_selector|
    log_of_requests_to_media_selector.contains_a_request_for? media
  end

  failure_message_for_should do |log_of_requests_to_media_selector|
    "expected Media Selector to have included a request for vpid/#{media.pid} or mediaset/#{media.media_set}\nLOG:\n#{log_of_requests_to_media_selector}"
  end
  
end
